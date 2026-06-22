<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Donate;
use App\Models\SRO\Account\TbUser;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $data = Voucher::getUserVoucher($request->user()->jid);

        return view('pages.voucher.index', [
            'data' => $data,
        ]);
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
        ]);

        $voucher = Voucher::where('code', $request->voucher_code)->first();

        if (!$voucher || $voucher->status == 'Disabled') {
            return redirect()->back()->with('error', 'Invalid voucher code.');
        }

        if ($voucher->status == 'Used') {
            return redirect()->back()->with('error', 'This voucher has already been used.');
        }

        if ($voucher->valid_date && Carbon::now()->greaterThan($voucher->valid_date)) {
            return redirect()->back()->with('error', 'This voucher has expired.');
        }

        $user = $request->user();

        TbUser::updateSilk($user->jid, $voucher->type, $voucher->amount);

        Donate::DonateLog([
            'method' => 'Voucher',
            'value' => $voucher->amount,
            'jid' => $user->jid,
        ]);

        $voucher->update(['jid' => $user->jid, 'status' => 'Used']);

        return redirect()->back()->with('success', 'Voucher redeemed successfully!');
    }
}

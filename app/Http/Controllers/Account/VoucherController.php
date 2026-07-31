<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Donate;
use App\Models\SRO\Account\TbUser;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $data = Voucher::getUserVoucher($request->user()->jid);

        return view('account.voucher.index', [
            'data' => $data,
        ]);
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string|max:50',
        ]);

        $voucher = Voucher::where('code', strtoupper(trim($request->voucher_code)))->first();
        if (!$voucher) {
            return redirect()->back()->with('error', 'Invalid voucher code.');
        }

        if ($voucher->jid === $request->user()->jid) {
            return redirect()->back()->with('error', 'You have already redeemed this voucher.');
        }

        if ($voucher->status == 'Disabled') {
            return redirect()->back()->with('error', 'Invalid voucher code.');
        }

        if ($voucher->status == 'Used') {
            return redirect()->back()->with('error', 'This voucher has already been used.');
        }

        if ($voucher->valid_date && Carbon::now()->greaterThan($voucher->valid_date)) {
            return redirect()->back()->with('error', 'This voucher has expired.');
        }

        $user = $request->user();

        DB::transaction(function () use ($user, $voucher) {
            TbUser::updateSilk($user->jid, $voucher->type, $voucher->amount);

            Donate::log([
                'method' => 'Voucher',
                'value' => $voucher->amount,
                'jid' => $user->jid,
            ]);

            $voucher->update(['jid' => $user->jid, 'status' => 'Used']);
        });

        Cache::forget("vouchers_user_{$user->jid}");

        return redirect()->back()->with('success', 'Voucher redeemed successfully!');
    }
}

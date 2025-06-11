<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $data = Voucher::all();
        return view('admin.vouchers', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
            'type' => 'required|integer',
        ]);

        $code = $this->generateUniqueCode();

        Voucher::create([
            'code' => $code,
            'amount' => $request->amount,
            'type' => $request->type,
            'valid_date' => $request->valid_date,
        ]);

        return redirect()->back()->with('success', 'Voucher created successfully!');
    }

    public function disable(Voucher $voucher)
    {
        $voucher->update(['status' => 'Disabled']);
        return redirect()->back()->with('success', 'Voucher deleted successfully!');
    }

    private function generateUniqueCode()
    {
        do {
            $code = strtoupper(implode('-', array_map(function () {
                return substr(bin2hex(random_bytes(4)), 0, 5);
            }, range(1, 5))));
        } while (Voucher::where('code', $code)->exists());

        return $code;
    }
}

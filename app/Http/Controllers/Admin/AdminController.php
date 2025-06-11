<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonateLog;
use App\Models\Referral;
use App\Models\SRO\Account\SkSilk;
use App\Models\SRO\Account\TbUser;
use App\Models\SRO\Portal\AphChangedSilk;
use App\Models\SRO\Shard\Char;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $userCount = TbUser::getTbUserCount();
        $charCount = Char::getCharCount();
        $totalGold = Char::getGoldSum();

        if (config('global.server.version') === 'vSRO') {
            $totalSilk = SkSilk::getSilkSum();
        } else {
            $totalSilk = AphChangedSilk::getSilkSum();
        }

        return view('admin.index', [
            'userCount' => $userCount,
            'charCount' => $charCount,
            'totalGold' => $totalGold,
            'totalSilk' => $totalSilk,
        ]);
    }

    public function referralLogs(Request $request)
    {
        $data = Referral::select('jid', DB::raw('SUM(points) as total_points'))
            ->groupBy('jid')
            ->orderByDesc('total_points')
            ->with('creator')
            ->take(50)
            ->get()
            ->map(function ($ref) {
                $referral = Referral::where('jid', $ref->jid)->latest()->first();
                return (object)[
                    'jid' => $ref->jid,
                    'total_points' => $ref->total_points,
                    'code' => $referral->code,
                    'ip' => $referral->ip,
                    'name' => optional($referral->creator)->username,
                ];
            });

        return view('admin.referral-logs', compact('data'));
    }

    public function donateLogs(Request $request)
    {
        $data = DonateLog::query()
            ->when($request->transaction_id, fn($q) =>
            $q->where('transaction_id', 'like', '%' . $request->transaction_id . '%'))
            ->when($request->method_type, fn($q) =>
            $q->where('method', $request->method_type))
            ->when($request->status, fn($q) =>
            $q->where('status', $request->status))
            ->when($request->jid, fn($q) =>
            $q->where('jid', $request->jid))
            ->when($request->ip, fn($q) =>
            $q->where('ip', 'like', '%' . $request->ip . '%'))
            ->latest()
            ->paginate(20);

        return view('admin.donate-logs', compact('data'));
    }
}

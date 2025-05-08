<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonateLog;
use App\Models\SRO\Account\TbUser;
use App\Models\SRO\Portal\AphChangedSilk;
use App\Models\SRO\Shard\Char;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $userCount = TbUser::getTbUserCount();
        $charCount = Char::getCharCount();
        $totalGold = Char::getGoldSum();
        $totalSilk = AphChangedSilk::getSilkSum();

        return view('admin.index', [
            'userCount' => $userCount,
            'charCount' => $charCount,
            'totalGold' => $totalGold,
            'totalSilk' => $totalSilk,
        ]);
    }

    public function donate_logs()
    {
        $data = DonateLog::all();
        return view('admin.donate-logs', compact('data'));
    }
}

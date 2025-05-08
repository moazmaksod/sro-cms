<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonateLog;
use App\Models\SRO\Account\SkSilk;
use App\Models\SRO\Account\TbUser;
use App\Models\SRO\Portal\AphChangedSilk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = TbUser::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('StrUserID', 'like', "%{$search}%");
            });
        }

        $data = $query->paginate(25);

        return view('admin.users.index', compact('data'));
    }

    public function view(TbUser $user)
    {
        $vipLevel = config('ranking.vip_level');
        return view('admin.users.view', [
            'user' => $user,
            'vipLevel' => $vipLevel,
        ]);
    }

    public function update(Request $request, TbUser $user)
    {
        $validated = $request->validate([
            'type' => 'required',
            'amount' => 'required|numeric',
        ]);

        if (config('global.server.version') === 'vSRO') {
            SkSilk::setSkSilk($user->JID, $validated['type'], $validated['amount']);
        } else {
            AphChangedSilk::setChangedSilk($user->PortalJID, $validated['type'], $validated['amount']);
        }

        DonateLog::setDonateLog('AdminPanel', Str::uuid(), 'true', 0, $validated['amount'], "Admin:{auth()->user()->username} Sent:{$validated['amount']} Silk", $user->JID, $request->ip());

        return back()->with('success', 'Silk have been Sent!');
    }
}

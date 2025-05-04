<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SRO\Account\SkSilk;
use App\Models\SRO\Account\TbUser;
use App\Models\SRO\Portal\AphChangedSilk;
use Illuminate\Http\Request;

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
        $vipLevel = config('global.ranking.vip_level');
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

        if (config('global.general.server.version') === 'vSRO') {
            SkSilk::setSkSilk($user->JID, $validated['type'], $validated['amount']);
        } else {
            AphChangedSilk::setChangedSilk($user->PortalJID, $validated['type'], $validated['amount']);
        }

        return back()->with('success', 'Silk have been Sent!');
    }
}

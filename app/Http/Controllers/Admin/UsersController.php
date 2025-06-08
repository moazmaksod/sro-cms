<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonateLog;
use App\Models\SRO\Account\SkSilk;
use App\Models\SRO\Account\TbUser;
use App\Models\SRO\Account\{BlockedUser, Punishment};
use App\Models\SRO\Portal\AphChangedSilk;
use App\Models\SRO\vPlus\{GameSecurityLog, AccountControl};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
        $donationLogs = DonateLog::where('jid', $user->JID)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $characters = $user->shardUser()->orderByDesc('CurLevel')->get();

        $lastLogin = GameSecurityLog::getLastLogin($user->JID);

        $activePunishment = Punishment::where('UserJID', $user->JID)
            ->where('Type', 1)
            ->where('BlockEndTime', '>', Carbon::now())
            ->orderByDesc('BlockEndTime')
            ->first();
            
        $punishmentHistory = Punishment::where('UserJID', $user->JID)
            ->where('Type', 1)
            ->orderByDesc('BlockEndTime')
            ->limit(5)
            ->get();   

        return view('admin.users.view', [
            'user' => $user,
            'vipLevel' => $vipLevel,
            'donationLogs' => $donationLogs,
            'characters' => $characters,
            'lastLogin' => $lastLogin,
            'activePunishment' => $activePunishment,
            'punishmentHistory' => $punishmentHistory,
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

        DonateLog::setDonateLog('AdminPanel', Str::uuid(), 'true', 0, $validated['amount'], "AdminJID:{$request->user()->jid} has sent:{$validated['amount']} silk", $user->JID, $request->ip());

        return back()->with('success', 'Silk have been Sent!');
    }
     public function updateEmail(Request $request, TbUser $user)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $user->Email = $validated['email'];
        $user->save();

        return back()->with('success', 'Email address updated successfully!');
    }

    public function updatePassword(Request $request, TbUser $user)
    {
        $validated = $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user->password = md5($validated['password']);
        $user->save();

        return back()->with('success', 'Password updated successfully!');
    }

    public function block(Request $request, TbUser $user)
    {
        $request->validate([
            'reason' => 'required',
            'duration' => 'required|integer|min:1',
        ]);

        $reason = $request->reason === 'Custom' ? $request->custom_reason : $request->reason;
        $now = Carbon::now();
        $end = $now->copy()->addHours((int) $request->duration);

        $punishment = Punishment::create([
            'UserJID' => $user->JID,
            'Type' => 1,
            'Executor' => 'Website',
            'Shard' => 0,
            'CharName' => ' ',
            'CharInfo' => '',
            'PosInfo' => '',
            'Guide' => $reason,
            'Description' => $reason,
            'RaiseTime' => $now,
            'BlockStartTime' => $now,
            'BlockEndTime' => $end,
            'Punishtime' => $now,
            'Status' => 1
        ]);

        $existing = BlockedUser::where('UserJID', $user->JID)
            ->where('Type', 1)
            ->first();

        if ($existing) {
            $existing->update([
                'SerialNo' => $punishment->SerialNo,
                'timeBegin' => $now,
                'timeEnd' => $end,
            ]);
        } else {
            BlockedUser::create([
                'UserJID' => $user->JID,
                'UserID' => $user->StrUserID,
                'Type' => 1,
                'SerialNo' => $punishment->SerialNo,
                'timeBegin' => $now,
                'timeEnd' => $end,
            ]);
        }

        AccountControl::disconnectUser($user->JID, $reason);

        return back()->with('success', 'Der Account wurde erfolgreich gesperrt.');
    }

    public function unban(Request $request, TbUser $user)
    {
        $now = Carbon::now();

        $existing = BlockedUser::where('UserJID', $user->JID)
            ->where('Type', 1)
            ->first();

        if ($existing) {
            $existing->update([
                'timeEnd' => $now,
            ]);

            return back()->with('success', 'Der Account wurde erfolgreich entsperrt.');
        }

        return back()->with('error', 'Keine aktive Sperre gefunden.');
    }
}

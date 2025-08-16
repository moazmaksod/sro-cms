<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonateLog;
use App\Models\SRO\Account\SkSilk;
use App\Models\SRO\Account\TbUser;
use App\Models\SRO\Account\BlockedUser;
use App\Models\SRO\Account\Punishment;
use App\Models\SRO\Portal\AphChangedSilk;
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

        $data = $query->paginate(20);

        return view('admin.users.index', compact('data'));
    }

    public function view(TbUser $user)
    {
        $vipLevel = config('ranking.vip_level');
        $donationLogs = DonateLog::where('jid', $user->JID)->orderByDesc('created_at')->limit(10)->get();
        $characters = $user->shardUser()->orderByDesc('CurLevel')->get();
        $activePunishment = Punishment::where('UserJID', $user->JID)->where('Type', 1)->where('BlockEndTime', '>', Carbon::now())->orderByDesc('BlockEndTime')->first();

        return view('admin.users.view', [
            'user' => $user,
            'vipLevel' => $vipLevel,
            'donationLogs' => $donationLogs,
            'characters' => $characters,
            'activePunishment' => $activePunishment,
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

        DonateLog::setDonateLog(
            'AdminPanel',
            Str::uuid(),
            'true',
            0,
            $validated['amount'],
            "AdminJID:{$request->user()->jid} has sent:{$validated['amount']} silk",
            $user->JID,
            $request->ip()
        );

        return back()->with('success', 'Silk have been Sent!');
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

        $punishment = Punishment::setPunishment($user->JID, $reason, $now, $end);
        $existing = BlockedUser::where('UserJID', $user->JID)->where('Type', 1)->first();
        if ($existing) {
            $existing->update([
                'SerialNo' => $punishment->SerialNo,
                'timeBegin' => $now,
                'timeEnd' => $end,
            ]);
        } else {
            BlockedUser::setBlockedUser($user->JID, $user->StrUserID, $punishment->SerialNo, $now, $end);
        }

        return back()->with('success', 'The account has been successfully suspended.');
    }

    public function unblock(Request $request, TbUser $user)
    {
        $now = Carbon::now();
        $existing = BlockedUser::where('UserJID', $user->JID)->where('Type', 1)->first();
        if ($existing) {
            $existing->update([
                'timeEnd' => $now,
            ]);

            return back()->with('success', 'The account has been successfully unblocked.');
        }

        return back()->with('error', 'No active Block found.');
    }
}

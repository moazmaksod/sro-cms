<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Donate;
use App\Models\Referral;
use App\Models\SRO\Account\TbUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReferralController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!config('global.referral.enabled', false), 404);
        $user = $request->user();

        $fingerprint = $request->query('fingerprint');

        $invite = Referral::createReferral($user, $fingerprint);

        if ($fingerprint && is_null($invite->fingerprint)) {
            $invite->update(['fingerprint' => $fingerprint]);
            Cache::forget("user_invites_created_{$user->jid}");
        }

        $totalPoints = $user->getInvitesCreated()->whereNotNull('invited_jid')->sum('points');
        $usedInvites = $user->getInvitesCreated()->whereNotNull('invited_jid')->load('invitedUser');
        $minimumRedeem = config('global.referral.minimum_redeem', 25);

        return view('account.referral.index', [
            'invite' => $invite,
            'usedInvites' => $usedInvites,
            'totalPoints' => $totalPoints,
            'minimumRedeem' => $minimumRedeem,
        ]);
    }

    public function redeem(Request $request)
    {
        $user = $request->user();
        $minimumRedeem = config('global.referral.minimum_redeem', 25);
        $invites = $user->InvitesCreated()->whereNotNull('invited_jid')->get();

        if(!config('global.referral.enabled', true)) {
            return back()->with('error', "Redeemed invites disabled.");
        }
        if ($invites->sum('points') < $minimumRedeem) {
            return back()->with('error', "You need at least {$minimumRedeem} points to redeem.");
        }

        DB::transaction(function () use ($user, $invites) {
            TbUser::updateSilk($user->jid, 0, $invites->sum('points'));

            Donate::log([
                'method' => 'Voucher',
                'value' => $invites->sum('points'),
                'jid' => $user->jid,
            ]);

            $user->invitesCreated()->whereNotNull('invited_jid')->update(['points' => 0]);
        });

        return back()->with('success', "{$invites->sum('points')} Silk has been added to your account!");
    }
}

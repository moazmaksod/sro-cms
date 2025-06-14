<?php

namespace App\Http\Controllers;

use App\Models\DonateLog;
use App\Models\SRO\Account\SkSilk;
use App\Models\SRO\Portal\AphChangedSilk;
use App\Models\Vote;
use App\Models\VoteLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;

class VoteController extends Controller
{
    public function index()
    {
        $data = Vote::getVotes();
        return view('profile.vote', compact('data'));
    }

    public function voting($id, Request $request)
    {
        $vote = Vote::findOrFail($id);
        $user = Auth::user();
        $now = Carbon::now();

        $ip = $request->ip();
        $fingerprint = $request->input('fingerprint');

        $voteLog = VoteLog::where('site', $vote->site)
            ->where(function($q) use ($ip, $fingerprint) {
                $q->where('ip', $ip)
                    ->orWhere('fingerprint', $fingerprint);
            })
            ->whereNotNull('expire')
            ->where('expire', '>', $now)
            ->first();

        if ($voteLog) {
            return redirect()->back()
                ->with('error', "You (or someone using your device) have already voted and must wait until {$voteLog->expire} to vote again for {$vote->site}.");
        }

        VoteLog::updateOrCreate(
            ['jid' => $user->jid, 'site' => $vote->site],
            ['ip' => $ip, 'fingerprint' => $fingerprint]
        );

        $url = str_replace('{JID}', $user->jid, $vote->url);
        return redirect()->away($url);
    }

    public function postback(Request $request)
    {
        $remoteIp = $request->server('HTTP_CF_CONNECTING_IP') ?? $request->ip();
        $vote = Vote::where('active', 1)->whereRaw("(',' + ip + ',') LIKE ?", ["%,$remoteIp,%"])->first();
        if (!$vote) {
            return response('Unauthorized IP', 401);
        }

        $data = $request->isMethod('POST') ? $request->post() : $request->query();
        $jid = $data[$vote->param] ?? null;
        if (!$jid) {
            return response('Missing user ID', 400);
        }

        if ($vote->site === 'gtop100' && (empty($data['Successful']) || abs($data['Successful']) !== 0)) {
            return response($data['Reason'] ?? 'Vote not successful', 200);
        }
        if (in_array($vote->site, ['arena-top100', 'silkroad-servers', 'private-server']) && (empty($data['voted']) || (int)$data['voted'] !== 1)) {
            return response("User $jid voted already today!", 200);
        }

        $now = Carbon::now();
        $timeout = $vote->timeout ?? 12;
        $voteLog = VoteLog::where('jid', $jid)->where('site', $vote->site)->first();

        if ($voteLog && $voteLog->expire && $now->lessThan(Carbon::parse($voteLog->expire))) {
            return response("User $jid must wait until {$voteLog->expire} to vote again for {$vote->site}.", 200);
        }

        $user = User::where('jid', $jid)->first();
        if (!$user) {
            return response('User not found', 404);
        }

        $rewardAmount = $vote->reward ?? 0;
        if (config('global.server.version') === 'vSRO') {
            SkSilk::setSkSilk($user->jid, 0, $rewardAmount);
        } else {
            AphChangedSilk::setChangedSilk($user->jid, 3, $rewardAmount);
        }

        DonateLog::setDonateLog(
            'Vote',
            (string) Str::uuid(),
            'true',
            0,
            $rewardAmount,
            "User: {$user->username} earned {$rewardAmount} silk for voting on {$vote->site}.",
            $user->jid,
            $remoteIp
        );

        VoteLog::updateOrCreate(
            ['jid' => $jid, 'site' => $vote->site],
            [
                'ip' => $remoteIp,
                'expire' => $now->addHours((int) $timeout),
            ]
        );

        return response("Vote registered and user rewarded!", 200);
    }
}

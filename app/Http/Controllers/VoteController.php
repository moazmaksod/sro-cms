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
        $data = Vote::all();
        return view('profile.vote', compact('data'));
    }

    public function voting($id, Request $request)
    {
        $vote = Vote::findOrFail($id);
        $user = Auth::user();

        VoteLog::create([
            'jid' => $user->jid,
            'site' => $vote->site,
            'ip'=> $request->ip(),
            'expire' => now()->addHours((int) $vote->timeout),
        ]);

        $url = str_replace('{JID}', $user->jid, $vote->url);
        return redirect()->away($url, 307);
    }

    public function postback(Request $request)
    {
        $remoteIp = $request->server('HTTP_CF_CONNECTING_IP') ?? $request->ip();

        $vote = Vote::where('active', true)
            ->where(function($query) use ($remoteIp) {
                $query->whereRaw("FIND_IN_SET(?, ip)", [$remoteIp])
                    ->orWhereRaw("FIND_IN_SET(?, REPLACE(ip, ' ', ''))", [$remoteIp]);
            })->first();

        if (!$vote) {
            Log::warning('Vote callback: Unauthorized IP', ['ip' => $remoteIp]);
            return response('Unauthorized IP', 401);
        }

        $data = $request->isMethod('POST') ? $request->post() : $request->query();
        $jid = $data[$vote->param] ?? null;

        if (!$jid) {
            Log::error("Vote callback: Missing user param '{$vote->param}' for site {$vote->site}", $data);
            return response('Missing user ID', 400);
        }

        if ($vote->site === 'gtop100' && (empty($data['Successful']) || abs($data['Successful']) !== 0)) {
            $msg = $data['Reason'] ?? 'Vote not successful';
            Log::info("Vote callback: gtop100 - $msg", $data);
            return response($msg, 200);
        }
        if (in_array($vote->site, ['arena-top100', 'silkroad-servers', 'private-server']) && (empty($data['voted']) || (int)$data['voted'] !== 1)) {
            $msg = "User $jid voted already today!";
            Log::info("Vote callback: {$vote->site} - $msg", $data);
            return response($msg, 200);
        }

        $now = Carbon::now();
        $timeout = $vote->timeout ?? 12;
        $voteLog = VoteLog::where('jid', $jid)->where('site', $vote->site)->first();

        if ($voteLog && $now->lessThan($voteLog->expire)) {
            $msg = "User $jid must wait until {$voteLog->expire} to vote again for {$vote->site}.";
            Log::info("Vote callback: Cooldown", ['jid' => $jid, 'site' => $vote->site]);
            return response($msg, 200);
        }

        $user = User::where('jid', $jid)->first();
        if (!$user) {
            Log::error("Vote callback: User not found", ['jid' => $jid]);
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

        $expire = $now->addHours($timeout);
        VoteLog::updateOrCreate(
            ['jid' => $jid, 'site' => $vote->site],
            [
                'ip' => $remoteIp,
                'expire' => $expire,
            ]
        );

        Log::info("Vote callback: Success", ['jid' => $jid, 'site' => $vote->site]);
        return response("Vote registered and user rewarded!", 200);
    }
}

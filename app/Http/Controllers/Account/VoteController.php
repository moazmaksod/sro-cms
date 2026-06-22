<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!config('global.vote.enabled', false), 404);
        $data = Vote::getVotes($request, session('fingerprint'));

        return view('pages.vote.index', compact('data'));
    }

    public function voting(string $site, Request $request)
    {
        $config = config("vote.$site");
        abort_if(!$config || !$config['enabled'], 404);

        $user = $request->user();

        $fingerprint = $request->input('fingerprint') ?? session('fingerprint');

        if (!$fingerprint) {
            return back()->with('error', 'Fingerprint not detected.');
        }

        session(['fingerprint' => $fingerprint]);

        if ($voteLog = Vote::activeVote($config['route'], $request->ip(), $fingerprint)) {
            return back()->with('error', "You have already voted. Please wait until {$voteLog->expire} to vote again for {$config['name']}.");
        }

        Vote::updateOrCreate(
            ['jid' => $user->jid, 'site' => $config['route']],
            ['ip' => $request->ip(), 'fingerprint' => $fingerprint]
        );

        $url = str_replace('{JID}', $user->jid, $config['url']);
        return redirect()->away($url);
    }
}

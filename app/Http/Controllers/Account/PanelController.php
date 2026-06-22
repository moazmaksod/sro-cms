<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Services\VoteService;
use Illuminate\Http\Request;

class PanelController extends Controller
{
    public function postback($site, Request $request, VoteService $voteService)
    {
        $config = config("vote.{$site}");

        if (!$config || !$config['enabled']) {
            return response('Vote Site not found or disabled.', 403);
        }

        $methodName = "postback" . ucfirst($site);
        if (!method_exists($voteService, $methodName)) {
            return response('Invalid postback method.', 403);
        }

        return $voteService->$methodName($request);
    }
}

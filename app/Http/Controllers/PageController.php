<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\News;
use App\Models\Pages;
use App\Models\SRO\Account\WebItemCertifyKey;
use App\Models\SRO\Log\LogChatMessage;
use App\Models\SRO\Log\LogEventChar;
use App\Models\SRO\Log\LogEventItem;
use App\Models\SRO\Log\LogEventSiegeFortress;
use App\Models\SRO\Log\LogInstanceWorldInfo;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PageController extends Controller
{
    public function index()
    {
        $data = News::getPosts();
        return view('pages.index', compact('data'));
    }

    public function post($slug)
    {
        $data = News::getPost($slug);
        if (!$data) {
            return redirect()->back();
        }

        return view('pages.view', compact('data'));
    }

    public function page($slug)
    {
        $data = Pages::getPage($slug);
        return view('pages.page', compact('data'));
    }

    public function download()
    {
        $data = Download::getDownloads();
        return view('pages.download', compact('data'));
    }

    public function timers(ScheduleService $scheduleService)
    {
        $data = $scheduleService->getEventSchedules();
        return view('pages.timers', compact('data'));
    }

    public function uniques()
    {
        $data = LogInstanceWorldInfo::getUniquesKill();
        $config = config('ranking.uniques');
        $characterRace = config('ranking.character_race');

        return view('pages.uniques', [
            'data' => $data,
            'config' => $config,
            'characterRace' => $characterRace,
        ]);
    }

    public function uniquesAdvanced()
    {
        $kills = LogInstanceWorldInfo::getUniquesKill(9999, 0, false);
        $ranking = LogInstanceWorldInfo::getUniqueRanking(9999, 0);
        $config = config('ranking.uniques');

        $data = [];
        foreach ($kills as $value) {
            $data[$value->Value][] = $value;
        }

        return view('pages.uniques-advanced', [
            'data' => $data,
            'ranking' => $ranking,
            'config' => $config,
        ]);
    }

    public function fortress()
    {
        $data = LogEventSiegeFortress::getFortressHistory(25);
        $config = config('widgets.fortress_war');

        return view('pages.fortress', [
            'data' => $data,
            'config' => $config,
        ]);
    }

    public function globals()
    {
        $data = LogChatMessage::getGlobalsHistory(25);
        return view('pages.globals', compact('data'));
    }

    public function sox_plus()
    {
        $data = LogEventItem::getLogEventItem('plus', 8, 8, 'Seal of Sun', null, 25);
        return view('pages.sox-plus', compact('data'));
    }

    public function sox_drop()
    {
        $data = LogEventItem::getLogEventItem('drop', null, 8, 'Seal of Sun', null, 25);
        return view('pages.sox-drop', compact('data'));
    }

    public function pvp_kills()
    {
        $data = LogEventChar::getKillLogs('pvp', 25);
        return view('pages.pvp-kills', compact('data'));
    }

    public function job_kills()
    {
        $data = LogEventChar::getKillLogs('job', 25);
        return view('pages.job-kills', compact('data'));
    }

    public function gateway(Request $request)
    {
        $user = $request->user();
        $data = WebItemCertifyKey::getCertifyKey($user->tbUser->JID);
        $config = config('global.server');
        $key = strtoupper(md5($data->UserJID.$data->Certifykey.$config['saltKey']));
        $data = "{$config['WebMallAddr']}?jid={$data->UserJID}&key={$key}&loc=us";

        return view('pages.gateway', [
            'data' => $data,
        ]);
    }
}

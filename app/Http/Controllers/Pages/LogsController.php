<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\SRO\Log\LogChatMessage;
use App\Models\SRO\Log\LogEventChar;
use App\Models\SRO\Log\LogEventItem;
use App\Models\SRO\Log\LogEventSiegeFortress;
use App\Models\SRO\Log\LogInstanceWorldInfo;
use App\Services\ScheduleService;

class LogsController extends Controller
{
    public function index()
    {
        $data = "Test";
        return view('pages.logs.index', compact('data'));
    }

    public function schedule(ScheduleService $scheduleService)
    {
        abort_if(!config('global.history.schedule', true), 404);
        $data = $scheduleService->getEventSchedules();
        return view('pages.logs.schedule', compact('data'));
    }

    public function unique()
    {
        abort_if(!config('global.history.unique', true), 404);
        $data = LogInstanceWorldInfo::getUniquesKill();
        return view('pages.logs.unique', compact('data'));
    }

    public function uniqueAdvanced()
    {
        abort_if(!config('global.history.unique_advanced', false), 404);
        $data = LogInstanceWorldInfo::getUniquesAdvanced(5);
        return view('pages.logs.unique-advanced', compact('data'));
    }

    public function fortress()
    {
        abort_if(!config('global.history.fortress', true), 404);
        $data = LogEventSiegeFortress::getFortressHistory(25);
        return view('pages.logs.fortress', compact('data'));
    }

    public function global()
    {
        abort_if(!config('global.history.global', true), 404);
        $data = LogChatMessage::getGlobalsHistory(25);
        return view('pages.logs.global', compact('data'));
    }

    public function plus()
    {
        abort_if(!config('global.history.plus', false), 404);
        $data = LogEventItem::getLogEventItem('plus', 8, 8, 'Seal of Sun', null, 25);
        return view('pages.logs.item-plus', compact('data'));
    }

    public function drop()
    {
        abort_if(!config('global.history.drop', false), 404);
        $data = LogEventItem::getLogEventItem('drop', null, 8, 'Seal of Sun', null, 25);
        return view('pages.logs.item-drop', compact('data'));
    }

    public function pvp()
    {
        abort_if(!config('global.history.pvp', false), 404);
        $data = LogEventChar::getKillLogs('pvp', 25);
        return view('pages.logs.pvp-kill', compact('data'));
    }

    public function job()
    {
        abort_if(!config('global.history.job', false), 404);
        $data = LogEventChar::getKillLogs('job', 25);
        return view('pages.logs.job-kill', compact('data'));
    }
}

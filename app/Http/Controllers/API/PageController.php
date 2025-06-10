<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SRO\Log\LogInstanceWorldInfo;
use App\Services\ScheduleService;

class PageController extends Controller
{
    public function timers(ScheduleService $scheduleService)
    {
        $data = $scheduleService->getEventSchedules();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function uniques()
    {
        $data = LogInstanceWorldInfo::getUniquesKill();
        $config = config('ranking.uniques');
        $characterRace = config('ranking.character_race');

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'config' => $config,
            'characterRace' => $characterRace,
        ]);
    }
}

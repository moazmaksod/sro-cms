<?php

namespace App\Services;

use App\Models\SRO\Shard\Schedule;
use Carbon\Carbon;

class ScheduleService
{
    public static function getEventSchedules(): array
    {
        $config = config('global.widgets.event_schedule.names');
        $schedulesDB = Schedule::getSchedules(array_keys($config));
        $now = Carbon::now();
        $result = [];

        $groupedSchedules = $schedulesDB->groupBy('ScheduleDefineIdx');

        foreach ($groupedSchedules as $ScheduleDefineIdx => $schedules) {
            $soonestEvent = null;

            foreach ($schedules as $schedule) {
                $hour = (int)$schedule->SubInterval_StartTimeHour;
                $minute = (int)$schedule->SubInterval_StartTimeMinute;
                $second = (int)$schedule->SubInterval_StartTimeSecond;
                $duration = (int)$schedule->SubInterval_DurationSecond;
                $dayOfWeek = (int)$schedule->SubInterval_DayOfWeek;
                $intervalType = (int)$schedule->MainInterval_Type;

                $nextStart = null;
                if ($intervalType === 1) {
                    $nextStart = $now->copy()->setTime($hour, $minute, $second);

                    if ($now->gte($nextStart)) {
                        $nextStart->addDay();
                    }
                } elseif ($intervalType === 3) {
                    $nextStart = $now->copy()->next($dayOfWeek - 1)->setTime($hour, $minute, $second);

                    if ($now->dayOfWeek === $dayOfWeek - 1) {
                        $todayStart = $now->copy()->setTime($hour, $minute, $second);

                        if ($now->lt($todayStart)) {
                            $nextStart = $todayStart;
                        }
                    }
                }

                if (!$nextStart) {
                    continue;
                }

                $nextEnd = $nextStart->copy()->addSeconds($duration);
                $isRunning = $now->between($nextStart, $nextEnd);

                if (!$soonestEvent || $nextStart->lt($soonestEvent['start'])) {
                    $soonestEvent = [
                        'start' => $nextStart,
                        'end' => $nextEnd,
                        'is_running' => $isRunning,
                        'duration' => $duration,
                        'description' => $config[$ScheduleDefineIdx] ?? $schedule->Description,
                    ];
                }
            }

            if ($soonestEvent) {
                $result[$ScheduleDefineIdx] = [
                    'timestamp' => $soonestEvent['start']->timestamp,
                    'is_running' => $soonestEvent['is_running'],
                    'duration' => $soonestEvent['duration'],
                    'description' => $soonestEvent['description'],
                    'start_time' => $soonestEvent['start']->toDateTimeString(),
                    'end_time' => $soonestEvent['end']->toDateTimeString(),
                ];
            }
        }

        return $result;
    }
}

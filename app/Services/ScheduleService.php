<?php

namespace App\Services;

use App\Models\SRO\Shard\Schedule;
use Carbon\Carbon;

class ScheduleService
{
    public static function getEventSchedules(): array
    {
        $now = Carbon::now();

        $config = config('global.widgets.event_schedule.names');
        $data = Schedule::getSchedules(array_keys($config));
        $grouped = $data->groupBy('ScheduleDefineIdx');
        $result = [];

        foreach ($grouped as $ScheduleDefineIdx => $schedules) {
            $soonestEvent = null;

            foreach ($schedules as $schedule) {
                $nextStart = null;

                if ((int)$schedule->MainInterval_Type === 1) {
                    $nextStart = $now->copy()->setTime(
                        (int)$schedule->SubInterval_StartTimeHour,
                        (int)$schedule->SubInterval_StartTimeMinute,
                        (int)$schedule->SubInterval_StartTimeSecond
                    );

                    $nextEnd = $nextStart->copy()->addSeconds((int)$schedule->SubInterval_DurationSecond);

                    if ($now->gte($nextEnd)) {
                        $nextStart->addDay();
                        $nextEnd = $nextStart->copy()->addSeconds((int)$schedule->SubInterval_DurationSecond);
                    }
                } elseif ((int)$schedule->MainInterval_Type === 3) {
                    $targetDay = (int)$schedule->SubInterval_DayOfWeek - 1;
                    $nextStart = $now->copy()->startOfDay()->setTime(
                        (int)$schedule->SubInterval_StartTimeHour,
                        (int)$schedule->SubInterval_StartTimeMinute,
                        (int)$schedule->SubInterval_StartTimeSecond
                    );

                    if ($now->dayOfWeek !== $targetDay) {
                        $nextStart->next($targetDay);
                    } elseif ($now->gte($nextStart->copy()->addSeconds((int)$schedule->SubInterval_DurationSecond))) {
                        $nextStart->addWeek();
                    }
                }

                if (!$nextStart) {
                    continue;
                }

                $nextEnd = $nextStart->copy()->addSeconds((int)$schedule->SubInterval_DurationSecond);
                $status = $now->between($nextStart, $nextEnd);

                if (!$soonestEvent || $status || (!$soonestEvent['status'] && $nextStart->lt($soonestEvent['start']))) {
                    $soonestEvent = ['start' => $nextStart, 'end' => $nextEnd, 'status' => $status];

                    $result[$ScheduleDefineIdx] = [
                        'idx' => $ScheduleDefineIdx,
                        'name' => $config[$ScheduleDefineIdx] ?? $schedule->Description,
                        'timestamp' => $nextStart->timestamp,
                        'duration' => (int)$schedule->SubInterval_DurationSecond,
                        'status' => $status,
                        'start' => $nextStart,
                        'end' => $nextEnd
                    ];
                }
            }
        }

        ksort($result, SORT_NUMERIC);
        return $result;
    }
}

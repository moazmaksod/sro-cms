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

                    if ($now->gte($nextStart)) {
                        $nextStart->addDay();
                    }
                } elseif ((int)$schedule->MainInterval_Type === 3) {
                    $nextStart = $now->copy()
                        ->next(
                            (int)$schedule->SubInterval_DayOfWeek - 1
                        )
                        ->setTime(
                        (int)$schedule->SubInterval_StartTimeHour,
                        (int)$schedule->SubInterval_StartTimeMinute,
                        (int)$schedule->SubInterval_StartTimeSecond
                    );

                    if ($now->dayOfWeek === (int)$schedule->SubInterval_DayOfWeek - 1) {
                        $todayStart = $now->copy()->setTime(
                            (int)$schedule->SubInterval_StartTimeHour,
                            (int)$schedule->SubInterval_StartTimeMinute,
                            (int)$schedule->SubInterval_StartTimeSecond
                        );

                        if ($now->lt($todayStart)) {
                            $nextStart = $todayStart;
                        }
                    }
                }

                if (!$nextStart) {
                    continue;
                }

                $nextEnd = $nextStart->copy()->addSeconds((int)$schedule->SubInterval_DurationSecond);
                $status = $now->between($nextStart, $nextEnd);

                if (!$soonestEvent || $nextStart->lt($soonestEvent['start'])) {
                    $soonestEvent = ['start' => $nextStart, 'end' => $nextEnd];

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

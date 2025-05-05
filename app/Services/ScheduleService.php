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
                $SubInterval_StartTimeHour = (int)$schedule->SubInterval_StartTimeHour;
                $SubInterval_StartTimeMinute = (int)$schedule->SubInterval_StartTimeMinute;
                $SubInterval_StartTimeSecond = (int)$schedule->SubInterval_StartTimeSecond;
                $SubInterval_DurationSecond = (int)$schedule->SubInterval_DurationSecond;

                $nextOccurrence = null;
                switch ((int)$schedule->MainInterval_Type) {
                    case 1: // Daily
                        $dateStart = $now->copy()->setTime(
                            $SubInterval_StartTimeHour,
                            $SubInterval_StartTimeMinute,
                            $SubInterval_StartTimeSecond
                        );

                        if ($now->lt($dateStart)) {
                            $nextOccurrence = $dateStart;
                        } else {
                            $nextOccurrence = $dateStart->addDay();
                        }
                        break;

                    case 3: // Weekly
                        $SubInterval_DayOfWeek = (int)$schedule->SubInterval_DayOfWeek - 1;
                        $nextDate = $now->copy()
                            ->next($SubInterval_DayOfWeek)
                            ->setTime(
                                $SubInterval_StartTimeHour,
                                $SubInterval_StartTimeMinute,
                                $SubInterval_StartTimeSecond
                            );

                        if ($now->dayOfWeek === $SubInterval_DayOfWeek) {
                            $dateStart = $now->copy()->setTime(
                                $SubInterval_StartTimeHour,
                                $SubInterval_StartTimeMinute,
                                $SubInterval_StartTimeSecond
                            );
                            if ($now->lt($dateStart)) {
                                $nextOccurrence = $dateStart;
                                break;
                            }
                        }

                        $nextOccurrence = $nextDate;
                        break;

                    default:
                        continue 2;
                }

                if (!$nextOccurrence) {
                    continue;
                }

                $dateEnd = $nextOccurrence->copy()->addSeconds($SubInterval_DurationSecond);

                // Determine if event is running (including during the duration period)
                $isRunning = $now->between($nextOccurrence, $dateEnd);

                if (!$soonestEvent || $nextOccurrence->lt($soonestEvent['dateStart'])) {
                    $soonestEvent = [
                        'dateStart' => $nextOccurrence,
                        'dateEnd' => $dateEnd,
                        'is_running' => $isRunning,
                        'duration' => $SubInterval_DurationSecond,
                        'description' => $config[$ScheduleDefineIdx] ?? '',
                    ];
                }
            }

            if ($soonestEvent) {
                $result[$ScheduleDefineIdx] = [
                    'timestamp' => $soonestEvent['dateStart']->timestamp,
                    'is_running' => $soonestEvent['is_running'],
                    'name' => $soonestEvent['description'],
                ];
            }
        }

        return $result;
    }
}

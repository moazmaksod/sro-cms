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
                // Use exact column names from table
                $SubInterval_StartTimeHour = (int)$schedule->SubInterval_StartTimeHour;
                $SubInterval_StartTimeMinute = (int)$schedule->SubInterval_StartTimeMinute;
                $SubInterval_StartTimeSecond = (int)$schedule->SubInterval_StartTimeSecond;
                $SubInterval_DurationSecond = (int)$schedule->SubInterval_DurationSecond;
                $SubInterval_DayOfWeek = (int)$schedule->SubInterval_DayOfWeek;
                $MainInterval_Type = (int)$schedule->MainInterval_Type;

                $nextOccurrence = null;

                switch ($MainInterval_Type) {
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
                        $carbonDayOfWeek = $SubInterval_DayOfWeek - 1; // Convert to Carbon's 0-6 format
                        $nextDate = $now->copy()
                            ->next($carbonDayOfWeek)
                            ->setTime(
                                $SubInterval_StartTimeHour,
                                $SubInterval_StartTimeMinute,
                                $SubInterval_StartTimeSecond
                            );

                        // Check if today is event day and hasn't started yet
                        if ($now->dayOfWeek === $carbonDayOfWeek) {
                            $todayStart = $now->copy()->setTime(
                                $SubInterval_StartTimeHour,
                                $SubInterval_StartTimeMinute,
                                $SubInterval_StartTimeSecond
                            );
                            if ($now->lt($todayStart)) {
                                $nextOccurrence = $todayStart;
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

                // Calculate exact end time using duration
                $dateEnd = $nextOccurrence->copy()->addSeconds($SubInterval_DurationSecond);

                // PROPER is_running calculation
                $is_running = ($now >= $nextOccurrence && $now <= $dateEnd);

                // Keep only the soonest event
                if (!$soonestEvent || $nextOccurrence->lt($soonestEvent['dateStart'])) {
                    $soonestEvent = [
                        'dateStart' => $nextOccurrence,
                        'dateEnd' => $dateEnd,
                        'is_running' => $is_running,
                        'SubInterval_DurationSecond' => $SubInterval_DurationSecond,
                        'Description' => $config[$ScheduleDefineIdx] ?? $schedule->Description,
                    ];
                }
            }

            if ($soonestEvent) {
                $result[$ScheduleDefineIdx] = [
                    'timestamp' => $soonestEvent['dateStart']->timestamp,
                    'is_running' => $soonestEvent['is_running'],
                    'SubInterval_DurationSecond' => $soonestEvent['SubInterval_DurationSecond'],
                    'Description' => $soonestEvent['Description'],
                    'start_time' => $soonestEvent['dateStart']->toDateTimeString(),
                    'end_time' => $soonestEvent['dateEnd']->toDateTimeString(),
                ];
            }
        }

        return $result;
    }
}

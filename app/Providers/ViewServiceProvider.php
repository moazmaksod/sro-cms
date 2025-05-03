<?php

namespace App\Providers;

use App\Models\Pages;
use App\Models\SRO\Account\ShardCurrentUser;
use App\Models\SRO\Log\LogChatMessage;
use App\Models\SRO\Log\LogInstanceWorldInfo;
use App\Models\SRO\Shard\Char;
use App\Models\SRO\Shard\Guild;
use App\Models\SRO\Shard\SiegeFortress;
use App\Services\ScheduleService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        try {
            View::composer(['layouts.header', 'layouts.navigation'], function ($view) {
                $view->with('pages', Pages::get());
            });

            View::composer(['partials.online-counter'], function ($view) {
                $view->with([
                    'online_counter' => ShardCurrentUser::getOnlineCounter(),
                    'max_player' => config('settings.max_player', 1000),
                    'fake_player' => config('settings.fake_player', 0),
                ]);
            });

            $eventSchedule = config('global.widgets.event_schedule');
            if($eventSchedule['enabled']) {
                View::composer(['partials.event-schedule'], function ($view, $eventSchedule) {
                    $view->with([
                        'eventSchedule', ScheduleService::getEventSchedules(),
                        'eventScheduleConfig', $eventSchedule,
                    ]);
                });
            }

            $fortressWar = config('global.widgets.fortress_war');
            if($fortressWar['enabled']) {
                View::composer(['partials.fortress-war'], function ($view, $fortressWar) {
                    $view->with([
                        'fortressWar', SiegeFortress::getFortressWar(),
                        'fortressWarConfig', $fortressWar,
                    ]);
                });
            }

            $globalsHistory = config('global.widgets.globals_history');
            if($globalsHistory['enabled']) {
                View::composer(['partials.globals-history'], function ($view, $globalsHistory) {
                    $view->with('globalsHistory', LogChatMessage::getGlobalsHistory($globalsHistory['limit']),);
                });
            }

            $uniqueHistory = config('global.widgets.unique_history');
            if($uniqueHistory['enabled']) {
                View::composer(['partials.unique-history'], function ($view, $uniqueHistory) {
                    $view->with('unique_history', LogInstanceWorldInfo::getUniquesKill($uniqueHistory['limit']));
                });
            }

            $topPlayer = config('global.widgets.top_player');
            if($topPlayer['enabled']) {
                View::composer(['partials.top-player'], function ($view, $topPlayer) {
                    $view->with('top_player', Char::getPlayerRanking($topPlayer['limit'], 0, ''));
                });
            }

            $topGuild = config('global.widgets.top_guild');
            if($topGuild['enabled']) {
                View::composer(['partials.top-guild'], function ($view, $topGuild) {
                    $view->with('top_guild', Guild::getGuildRanking($topGuild['limit'], 0, ''));
                });
            }

        } catch (QueryException $e) {
            // Error: Something Wrong.
        }
    }
}

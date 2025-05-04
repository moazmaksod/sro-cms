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

            $slider = config('global.general.sliders');
            View::composer(['partials.carousel'], function ($view, $slider) {
                $view->with('config', $slider);
            });

            View::composer(['partials.online-counter'], function ($view) {
                $view->with([
                    'onlinePlayer' => ShardCurrentUser::getOnlineCounter(),
                    'maxPlayer' => config('settings.max_player', 1000),
                    'fakePlayer' => config('settings.fake_player', 0)
                ]);
            });

            $discord = config('global.widgets.discord');
            if($discord['enabled']) {
                View::composer(['partials.discord'], function ($view, $discord) {
                    $view->with('config', $discord);
                });
            }

            $serverInfo = config('global.widgets.server_info');
            if($serverInfo['enabled']) {
                View::composer(['partials.server-info'], function ($view, $serverInfo) {
                    $view->with('config', $serverInfo);
                });
            }

            $eventSchedule = config('global.widgets.event_schedule');
            if($eventSchedule['enabled']) {
                View::composer(['partials.event-schedule'], function ($view, $eventSchedule) {
                    $view->with([
                        'data' => ScheduleService::getEventSchedules(),
                        'config' => $eventSchedule
                    ]);
                });
            }

            $fortressWar = config('global.widgets.fortress_war');
            if($fortressWar['enabled']) {
                View::composer(['partials.fortress-war'], function ($view, $fortressWar) {
                    $view->with([
                        'data' => SiegeFortress::getFortressWar(),
                        'config' => $fortressWar
                    ]);
                });
            }

            $globalsHistory = config('global.widgets.globals_history');
            if($globalsHistory['enabled']) {
                View::composer(['partials.globals-history'], function ($view, $globalsHistory) {
                    $view->with([
                        'data' => LogChatMessage::getGlobalsHistory($globalsHistory['limit']),
                        'config' => $globalsHistory
                    ]);
                });
            }

            $uniqueHistory = config('global.widgets.unique_history');
            $uniques = config('global.ranking.uniques');
            if($uniqueHistory['enabled']) {
                View::composer(['partials.unique-history'], function ($view, $uniqueHistory, $uniques) {
                    $view->with([
                        'data' => LogInstanceWorldInfo::getUniquesKill($uniqueHistory['limit']),
                        'config' => $uniqueHistory,
                        'uniques' => $uniques
                    ]);
                });
            }

            $topPlayer = config('global.widgets.top_player');
            $topImage = config('global.ranking.top_image');
            if($topPlayer['enabled']) {
                View::composer(['partials.top-player'], function ($view, $topPlayer, $topImage) {
                    $view->with([
                        'data' => Char::getPlayerRanking($topPlayer['limit']),
                        'config' => $topPlayer,
                        'image' => $topImage
                    ]);
                });
            }

            $topGuild = config('global.widgets.top_guild');
            $topImage = config('global.ranking.top_image');
            if($topGuild['enabled']) {
                View::composer(['partials.top-guild'], function ($view, $topGuild, $topImage) {
                    $view->with([
                        'data' => Guild::getGuildRanking($topGuild['limit']),
                        'config' => $topGuild,
                        'image' => $topImage
                    ]);
                });
            }

        } catch (QueryException $e) {
            // Error: Something Wrong.
        }
    }
}

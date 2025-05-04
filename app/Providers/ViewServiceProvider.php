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

            $languages = config('global.general.languages');
            View::composer(['layouts.header', 'layouts.navigation'], function ($view) use ($languages) {
                $view->with('languages', $languages);
            });

            $sliders = config('global.general.sliders');
            View::composer(['partials.carousel'], function ($view) use ($sliders) {
                $view->with('sliders', $sliders);
            });

            $footer = config('global.general.footer');
            View::composer(['layouts.footer'], function ($view) use ($footer) {
                $view->with('footer', $footer);
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
                View::composer(['partials.discord'], function ($view) use ($discord) {
                    $view->with('discord', $discord);
                });
            }

            $serverInfo = config('global.widgets.server_info');
            if($serverInfo['enabled']) {
                View::composer(['partials.server-info'], function ($view) use ($serverInfo) {
                    $view->with('serverInfo', $serverInfo);
                });
            }

            $eventScheduleConfig = config('global.widgets.event_schedule');
            if($eventScheduleConfig['enabled']) {
                View::composer(['partials.event-schedule'], function ($view) use ($eventScheduleConfig) {
                    $view->with([
                        'eventSchedule' => ScheduleService::getEventSchedules(),
                        'eventScheduleConfig' => $eventScheduleConfig
                    ]);
                });
            }

            $fortressWarConfig = config('global.widgets.fortress_war');
            if($fortressWarConfig['enabled']) {
                View::composer(['partials.fortress-war'], function ($view) use ($fortressWarConfig) {
                    $view->with([
                        'fortressWar' => SiegeFortress::getFortressWar(),
                        'fortressWarConfig' => $fortressWarConfig
                    ]);
                });
            }

            $globalsHistoryConfig = config('global.widgets.globals_history');
            if($globalsHistoryConfig['enabled']) {
                View::composer(['partials.globals-history'], function ($view) use ($globalsHistoryConfig) {
                    $view->with([
                        'globalsHistory' => LogChatMessage::getGlobalsHistory($globalsHistoryConfig['limit']),
                        'globalsHistoryConfig' => $globalsHistoryConfig
                    ]);
                });
            }

            $uniqueHistoryConfig = config('global.widgets.unique_history');
            $uniquesList = config('global.ranking.uniques');
            if($uniqueHistoryConfig['enabled']) {
                View::composer(['partials.unique-history'], function ($view) use ($uniqueHistoryConfig, $uniquesList) {
                    $view->with([
                        'uniqueHistory' => LogInstanceWorldInfo::getUniquesKill($uniqueHistoryConfig['limit']),
                        'uniqueHistoryConfig' => $uniqueHistoryConfig,
                        'uniquesList' => $uniquesList
                    ]);
                });
            }

            $topPlayerConfig = config('global.widgets.top_player');
            $topImage = config('global.ranking.top_image');
            if($topPlayerConfig['enabled']) {
                View::composer(['partials.top-player'], function ($view) use ($topPlayerConfig, $topImage) {
                    $view->with([
                        'topPlayer' => Char::getPlayerRanking($topPlayerConfig['limit']),
                        'topPlayerConfig' => $topPlayerConfig,
                        'topImage' => $topImage
                    ]);
                });
            }

            $topGuildConfig = config('global.widgets.top_guild');
            $topImage = config('global.ranking.top_image');
            if($topGuildConfig['enabled']) {
                View::composer(['partials.top-guild'], function ($view) use ($topGuildConfig, $topImage) {
                    $view->with([
                        'topGuild' => Guild::getGuildRanking($topGuildConfig['limit']),
                        'topGuildConfig' => $topGuildConfig,
                        'topImage' => $topImage
                    ]);
                });
            }

        } catch (QueryException $e) {
            // Error: Something Wrong.
        }
    }
}

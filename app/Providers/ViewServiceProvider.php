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

            View::composer(['layouts.sidebar', 'layouts.sidebar-right'], function ($view) {
                $view->with([
                    'online_counter' => ShardCurrentUser::getOnlineCounter(),
                    'max_player' => config('settings.max_player'),
                    'fake_player' => config('settings.fake_player'),
                ]);
            });

            if(config('global.widgets.event_schedule.enable')) {
                View::composer(['layouts.sidebar', 'layouts.sidebar-right'], function ($view) {
                    $view->with('event_schedule', ScheduleService::getEventSchedules());
                });
            }
            if(config('global.widgets.fortress_war.enable')) {
                View::composer(['layouts.*', 'partials.*'], function ($view) {
                    $view->with('fortress_war', SiegeFortress::getFortress());
                });
            }
            if(config('global.widgets.globals_history.enable')) {
                View::composer(['layouts.sidebar', 'layouts.sidebar-right'], function ($view) {
                    $view->with('globals_history', LogChatMessage::getGlobalsHistory(config('global.widgets.globals_history.limit')));
                });
            }
            if(config('global.widgets.unique_history.enable')) {
                View::composer(['layouts.sidebar', 'layouts.sidebar-right'], function ($view) {
                    $view->with('unique_history', LogInstanceWorldInfo::getUniques($limit = config('global.widgets.unique_history.limit')));
                });
            }
            if(config('global.widgets.top_player.enable')) {
                View::composer(['layouts.sidebar', 'layouts.sidebar-right'], function ($view) {
                    $view->with('top_player', Char::getPlayerRanking(config('global.widgets.top_player.limit'), 0, ''));
                });
            }
            if(config('global.widgets.top_guild.enable')) {
                View::composer(['layouts.sidebar', 'layouts.sidebar-right'], function ($view) {
                    $view->with('top_guild', Guild::getGuildRanking(config('global.widgets.top_guild.limit'), 0, ''));
                });
            }

        } catch (QueryException $e) {
            // Error: Something Wrong.
        }
    }
}

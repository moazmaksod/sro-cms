<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!app()->runningInConsole()) {
            try {
                //Timezone
                date_default_timezone_set(config('global.general.options.timezone'));
                //
                Blade::if('admin', function () {return auth()->check() && auth()->user()->role?->is_admin;});
                View::getFinder()->prependLocation(resource_path("themes/".config('global.general.options.theme').'/views'));
                Config::set('settings', Setting::pluck('value', 'key')->toArray());

            } catch (QueryException $e) {
                // Error: Something Error.
            }
        }
    }
}

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
            Config::set('settings', Setting::pluck('value', 'key')->toArray());
        }

        Blade::if('admin', function () {return auth()->check() && auth()->user()->role?->is_admin;});
        date_default_timezone_set(config('settings.timezone', config('app.timezone')));
        View::getFinder()->prependLocation(resource_path("themes/".config('settings.theme').'/views'));
    }
}

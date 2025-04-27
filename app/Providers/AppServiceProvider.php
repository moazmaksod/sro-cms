<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\App;
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
        if ($this->app->runningInConsole()) {
            return;
        }

        Blade::if('admin', function () {return auth()->check() && auth()->user()->role?->is_admin;});
        Config::set('settings', Setting::pluck('value', 'key')->toArray());
        View::getFinder()->prependLocation(resource_path("themes/".config('settings.theme').'/views'));
        date_default_timezone_set(config('settings.timezone', config('app.timezone')));
        App::setLocale(config('settings.locale', 'en'));
    }
}

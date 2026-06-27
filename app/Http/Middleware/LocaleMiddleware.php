<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class LocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        if (config('settings.default_locale', 'switch') === 'switch') {
            $locale = Session::get('locale', config('app.locale'));
        } else {
            $locale = config('settings.default_locale');
            Session::put('locale', $locale);
        }

        App::setLocale($locale);

        return $next($request);
    }
}

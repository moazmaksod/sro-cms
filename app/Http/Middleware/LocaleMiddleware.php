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
        $locale = $request->segment(1);

        if ($locale && array_key_exists($locale, config('global.languages', []))) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        } else {
            $locale = Session::get('locale', config('app.locale'));
            App::setLocale($locale);
        }

        URL::defaults(['locale' => App::getLocale()]);

        $request->route()?->forgetParameter('locale');

        return $next($request);
    }
}

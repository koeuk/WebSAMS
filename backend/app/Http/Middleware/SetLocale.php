<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    private const SUPPORTED = ['en', 'km', 'zh'];

    public function handle(Request $request, Closure $next)
    {
        $sessionLocale = $request->hasSession() ? $request->session()->get('locale') : null;

        $locale = $request->header('X-Locale')
            ?? $request->cookie('locale')
            ?? $sessionLocale
            ?? config('app.locale');

        if (!in_array($locale, self::SUPPORTED, true)) {
            $locale = config('app.locale');
        }

        app()->setLocale($locale);

        if ($request->hasSession()) {
            $request->session()->put('locale', $locale);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LanguageSwitcher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \App::setLocale(session('applocale') ?? 'en');

        return $next($request);
    }
}

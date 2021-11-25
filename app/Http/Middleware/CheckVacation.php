<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckVacation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(session()->get('profile') && session()->get('profile')->vacation && session()->get('profile')->vacation_until) {
            return redirect('/vacation/' . strtotime(session()->get('profile')->vacation_until));
        } else {
            return $next($request);
        }
    }
}

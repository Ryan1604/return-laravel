<?php

namespace App\Http\Middleware;

use Closure;

class CheckSession
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
        if(auth()->check() && auth()->user()->isActiveSession()) {
            auth()->logout();

            return redirect('/')->with('info', 'Your session has expired, please login again!');
        }
        return $next($request);
    }
}

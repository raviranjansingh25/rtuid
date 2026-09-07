<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class VenderMiddleware
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
        if (Auth::guard('vender')->check()) {
            return redirect()->route('coach_dashboard');
        }
        return $next($request);
    }
}

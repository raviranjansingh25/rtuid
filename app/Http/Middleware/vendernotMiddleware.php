<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class vendernotMiddleware
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

        if (!Auth::guard('vender')->check()) {
            return redirect()->route('coachlogin');
        }
        return $next($request);
    }
}

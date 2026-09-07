<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class UsernotMiddleware
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
        $current_url = url()->current();
        if (!Auth::guard('web')->check()) {
            return redirect('/')->with('current_url', $current_url);
        }
        return $next($request);
    }
}

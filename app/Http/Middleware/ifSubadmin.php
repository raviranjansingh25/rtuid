<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\Admin;

class ifSubadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard = 'subadmin')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect()->route('subadminlogin');
        }

        $admin = Admin::find(Auth::guard($guard)->id());

        if (!$admin || (int) $admin->role !== 2 || (int) $admin->status !== 1) {
            Auth::guard($guard)->logout();
            return redirect()->route('subadminlogin');
        }

        Auth::guard($guard)->setUser($admin);

        return $next($request);
    }
}

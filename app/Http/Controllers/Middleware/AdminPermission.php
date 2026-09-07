<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\GroupPermission;
use Auth;

class AdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $role = Auth::guard('admin')->user();
        $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        
        list($controller, $action) = explode('@', $controllerAction);
        
        $user_permission = GroupPermission::where('subadmin_id',$role->id)->where('controller',$controller)->first();
        // p($user_permission);
        if (empty($user_permission) && $role->id!=1) {
            
            return redirect()->route('denied');
        }
        return $next($request);
    }
}

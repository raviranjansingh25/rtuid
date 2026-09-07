<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class CheckVenderMiddleware
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
        $user= auth()->guard('api')->user();
        $userdata = User::where('id',$user->id)->first();
        if(!empty($userdata)){
            return $next($request);
        }else{
            $user = Auth::user()->token();
            $user->revoke();
            abort(response()->json(
            [
                'status' => 403,
                'message' => 'UnAuthenticated',
                'data' => null
            ], 401));
        }   
    }
}

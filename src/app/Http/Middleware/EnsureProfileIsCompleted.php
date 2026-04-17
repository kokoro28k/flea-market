<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureProfileIsCompleted
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
        if($request->routeIs('logout')){
            return $next($request);
        }

        if (auth()->check()){
            if (!auth()->user()->hasVerifiedEmail()){
                return redirect()->route('verification.notice');
            }
        if (!auth()->user()->profile_completed){
            if (!$request->is('mypage/profile')){
                return redirect('mypage/profile');
                }
            }
        }

        return $next($request);
    }
}

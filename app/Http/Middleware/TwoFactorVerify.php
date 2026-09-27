<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class TwoFactorVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        switch ($guard) {
            case 'admin':
                $sessionCookie = "admin_2fa";
                $route = route('admin.2fa.verify');
                break;
            case 'reviewer':
                $sessionCookie = "reviewer_2fa";
                $route = route('reviewer.2fa.verify');
                break;
            default:
                $sessionCookie = "user_2fa";
                $route = route('2fa.verify');
        }

        if (Auth::guard($guard)->check() && Auth::guard($guard)->user()->google2fa_status &&
            !$request->session()->has($sessionCookie) &&
            session($sessionCookie) != encrypt(Auth::guard($guard)->user()->id)) {
            return redirect($route);
        }

        return $next($request);
    }
}

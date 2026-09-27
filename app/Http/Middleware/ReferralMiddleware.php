<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class ReferralMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref')) {
            if (@settings('referral')->status) {
                if (!Auth::user()) {
                    $referrer = User::where('username', $request->ref)->first();
                    if ($referrer) {
                        Cookie::queue('_ref', $referrer->username);
                    }
                }
            }
            return redirect()->route('home');
        }
        return $next($request);
    }
}

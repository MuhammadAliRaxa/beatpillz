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
        if ($request->filled('ref')) {
            if (@settings('referral')->status) {
                $currentUsername = Auth::check() ? Auth::user()->username : null;
                $refCode = strtolower(trim($request->ref));

                if (!$currentUsername || strtolower($currentUsername) !== $refCode) {
                    $referrer = User::where('username', $refCode)->first();
                    if ($referrer) {
                        // Store referral code in cookie for 90 days (129,600 minutes)
                        Cookie::queue('_ref', $referrer->username, 60 * 24 * 90);

                        // Also store in session as a fallback
                        if ($request->hasSession()) {
                            $request->session()->put('ref', $referrer->username);
                        }

                        // If guest opens a referral link on any non-register page, redirect directly to the register page
                        if (!Auth::check() && !$request->is('register') && !$request->is('api/*')) {
                            return redirect()->route('register', ['ref' => $referrer->username]);
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}

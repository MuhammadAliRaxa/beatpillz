<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::shouldUse($guard);
                $request->setUserResolver(function () use ($guard) {
                    return Auth::guard($guard)->user();
                });
                return $next($request);
            }
        }

        if ($request->expectsJson() || $request->ajax() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $firstGuard = $guards[0] ?? null;
        switch ($firstGuard) {
            case 'admin':
                return redirect()->guest(route('admin.login'));
            case 'reviewer':
                return redirect()->guest(route('reviewer.login'));
            default:
                return redirect()->guest(route('login'));
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->expectsJson() || $request->ajax() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            } else {
                switch ($guard) {
                    case 'admin':
                        return redirect()->guest(route('admin.login'));
                        break;
                    case 'reviewer':
                        return redirect()->guest(route('reviewer.login'));
                        break;
                    default:
                        return redirect()->guest(route('login'));
                }
            }
        }
        return $next($request);
    }
}

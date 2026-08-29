<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KycVerificationRequired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = authUser();

        if ($user && $user->isKycRequired()) {
            toastr()->info(translate('Please complete the KYC verification'));
            return redirect()->route('workspace.settings.kyc');
        }

        return $next($request);
    }
}

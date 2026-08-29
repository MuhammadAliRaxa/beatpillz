<?php

namespace App\Http\Middleware;

use App\Classes\IPLookup;
use App\Models\Currency;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CurrencyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('system.install.complete') && request()->segment(1) != adminPath()
            && request()->segment(1) != reviewerPath()) {
            if (!$request->hasCookie('currency')) {
                $clientCurrency = app(IPLookup::class)->lookup(getIp())->currency;

                $currency = Currency::where('code', $clientCurrency)->first();
                if ($currency) {
                    config(['app.currency' => $currency->code]);
                    Cookie::queue('currency', $currency->code, 60 * 24 * 30);
                }
            } else {
                $currencyCode = $request->cookie('currency');
                $currency = Currency::where('code', $currencyCode)->first();
                if ($currency) {
                    config(['app.currency' => $request->cookie('currency')]);
                }
            }
        }

        return $next($request);
    }
}

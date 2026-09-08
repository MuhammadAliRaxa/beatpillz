<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Classes\Country;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    /**
     * Mobile bootstrap configuration metadata.
     */
    public function index()
    {
        $currencies = Currency::all();
        $defaultCurrency = function_exists('defaultCurrency') ? @defaultCurrency() : null;

        $siteName = @settings('general') ? data_get(settings('general'), 'site_name', 'Beat Pillz') : 'Beat Pillz';
        $siteUrl = url('/');
        $contactEmail = @settings('contact') ? data_get(settings('contact'), 'email', 'support@beatpillz.com') : 'support@beatpillz.com';

        return response()->json([
            'success' => true,
            'config'  => [
                'site_name'        => $siteName,
                'site_url'         => $siteUrl,
                'default_currency' => $defaultCurrency ? [
                    'code'     => $defaultCurrency->code,
                    'symbol'   => $defaultCurrency->symbol,
                    'position' => (int) $defaultCurrency->position,
                    'rate'     => (float) $defaultCurrency->rate,
                ] : [
                    'code'     => 'USD',
                    'symbol'   => '$',
                    'position' => 1,
                    'rate'     => 1.0,
                ],
                'currencies'       => $currencies->map(function ($curr) {
                    return [
                        'code'     => $curr->code,
                        'symbol'   => $curr->symbol,
                        'position' => (int) $curr->position,
                        'rate'     => (float) $curr->rate,
                        'icon'     => $curr->icon ? asset($curr->icon) : null,
                    ];
                }),
                'legal_links'      => [
                    'terms_of_use'   => url('/terms-of-use'),
                    'privacy_policy' => url('/privacy-policy'),
                    'refund_policy'  => url('/refund-policy'),
                ],
                'contact'          => [
                    'email' => $contactEmail,
                    'phone' => null,
                ],
            ],
        ], 200);
    }

    /**
     * Get all available countries for selection.
     */
    public function countries()
    {
        $countries = [];
        foreach (Country::all() as $code => $name) {
            $countries[] = [
                'code' => $code,
                'name' => $name,
            ];
        }

        return response()->json([
            'success'   => true,
            'countries' => $countries,
        ], 200);
    }
}

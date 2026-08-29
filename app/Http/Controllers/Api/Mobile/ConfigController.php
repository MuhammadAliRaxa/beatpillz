<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Page;
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

        $general = @settings('general');
        $links = @settings('links');
        $contact = @settings('contact');

        return response()->json([
            'success' => true,
            'config'  => [
                'site_name'        => $general ? $general->site_name : 'Beat Pillz',
                'site_url'         => url('/'),
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
                    'terms_of_use'   => $links && $links->terms_of_use_link ? $links->terms_of_use_link : url('/terms-of-use'),
                    'privacy_policy' => $links && $links->privacy_policy_link ? $links->privacy_policy_link : url('/privacy-policy'),
                    'refund_policy'  => $links && $links->refund_policy_link ? $links->refund_policy_link : url('/refund-policy'),
                ],
                'contact'          => [
                    'email' => $contact && $contact->email ? $contact->email : 'support@beatpillz.com',
                    'phone' => $contact && $contact->phone ? $contact->phone : null,
                ],
            ],
        ], 200);
    }
}

<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        if (!$this->resource) {
            return [];
        }

        // 1. Safely parse address (array, JSON string, or object)
        $address = [];
        if (is_array($this->address)) {
            $address = $this->address;
        } elseif (is_string($this->address)) {
            $address = json_decode($this->address, true) ?? [];
        } elseif (is_object($this->address)) {
            $address = (array) $this->address;
        }

        // 2. Safely parse social links (check social_links column first)
        $socialLinks = [];
        $rawSocial = $this->social_links ?? $this->profile_social_links ?? null;
        if (is_array($rawSocial)) {
            $socialLinks = $rawSocial;
        } elseif (is_string($rawSocial)) {
            $socialLinks = json_decode($rawSocial, true) ?? [];
        }

        // 3. Safely format currency
        $currency = 'USD';
        try {
            if (function_exists('defaultCurrency') && defaultCurrency() && isset(defaultCurrency()->code)) {
                $currency = defaultCurrency()->code;
            }
        } catch (\Throwable $e) {}

        // 4. Safely format created_at date without crashing on raw date strings
        $createdAt = null;
        if ($this->created_at) {
            $createdAt = (is_object($this->created_at) && method_exists($this->created_at, 'toISOString'))
                ? $this->created_at->toISOString()
                : (string) $this->created_at;
        }

        // 5. Safely resolve country_name without crashing if Country::get() fails on full country names like 'Nigeria'
        $countryName = $address['country'] ?? ($this->country ?? '');
        try {
            if (method_exists($this->resource, 'getCountry')) {
                $resolved = $this->getCountry();
                if (!empty($resolved)) {
                    $countryName = $resolved;
                }
            }
        } catch (\Throwable $e) {
            $countryName = $address['country'] ?? ($this->country ?? '');
        }

        return [
            'id'                  => $this->id,
            'firstname'           => $this->firstname,
            'lastname'            => $this->lastname,
            'fullname'            => method_exists($this->resource, 'getName') ? $this->getName() : trim(($this->firstname ?? '') . ' ' . ($this->lastname ?? '')),
            'username'            => $this->username,
            'email'               => $this->email,
            'avatar'              => $this->avatar ? asset($this->avatar) : null,
            'profile_cover'       => $this->profile_cover ? asset($this->profile_cover) : null,
            'profile_heading'     => $this->profile_heading,
            'profile_description' => $this->profile_description,
            'is_author'           => (bool) $this->is_author,
            'is_featured_author'  => (bool) $this->is_featured_author,
            'exclusivity'         => $this->exclusivity,
            'address'             => [
                'line_1'       => $address['line_1'] ?? $address['address_1'] ?? '',
                'line_2'       => $address['line_2'] ?? $address['address_2'] ?? '',
                'city'         => $address['city'] ?? '',
                'state'        => $address['state'] ?? '',
                'zip'          => $address['zip'] ?? $address['postal_code'] ?? '',
                'country'      => $address['country'] ?? ($this->country ?? ''),
                'country_name' => $countryName,
            ],
            'balance'             => (float) $this->balance,
            'currency'            => $currency,
            'kyc_status'          => (int) $this->kyc_status,
            'total_sales'         => (int) $this->total_sales,
            'total_sales_amount'  => (float) $this->total_sales_amount,
            'total_reviews'       => (int) $this->total_reviews,
            'avg_reviews'         => (float) $this->avg_reviews,
            'total_followers'     => (int) $this->total_followers,
            'social_links'        => $socialLinks,
            'created_at'          => $createdAt,
        ];
    }
}

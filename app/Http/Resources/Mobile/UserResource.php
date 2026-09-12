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

        // Safely parse address as an array whether it is stored as array, JSON string, or object
        $address = [];
        if (is_array($this->address)) {
            $address = $this->address;
        } elseif (is_string($this->address)) {
            $address = json_decode($this->address, true) ?? [];
        } elseif (is_object($this->address)) {
            $address = (array) $this->address;
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
                'country'      => $address['country'] ?? '',
                'country_name' => method_exists($this->resource, 'getCountry') ? ($this->getCountry() ?? '') : ($address['country'] ?? ''),
            ],
            'balance'             => (float) $this->balance,
            'currency'            => (function_exists('defaultCurrency') && defaultCurrency()) ? defaultCurrency()->code : 'USD',
            'kyc_status'          => (int) $this->kyc_status,
            'total_sales'         => (int) $this->total_sales,
            'total_sales_amount'  => (float) $this->total_sales_amount,
            'total_reviews'       => (int) $this->total_reviews,
            'avg_reviews'         => (float) $this->avg_reviews,
            'total_followers'     => (int) $this->total_followers,
            'social_links'        => is_string($this->profile_social_links) ? (json_decode($this->profile_social_links, true) ?? []) : ($this->profile_social_links ?? []),
            'created_at'          => $this->created_at ? $this->created_at->toISOString() : null,
        ];
    }
}

<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                  => $this->id,
            'firstname'           => $this->firstname,
            'lastname'            => $this->lastname,
            'fullname'            => $this->getName(),
            'username'            => $this->username,
            'email'               => $this->email,
            'avatar'              => $this->avatar ? asset($this->avatar) : null,
            'profile_cover'       => $this->profile_cover ? asset($this->profile_cover) : null,
            'profile_heading'     => $this->profile_heading,
            'profile_description' => $this->profile_description,
            'is_author'           => (bool) $this->is_author,
            'is_featured_author'  => (bool) $this->is_featured_author,
            'balance'             => (float) $this->balance,
            'currency'            => function_exists('defaultCurrency') ? @defaultCurrency()->code : 'USD',
            'kyc_status'          => (int) $this->kyc_status,
            'total_sales'         => (int) $this->total_sales,
            'total_sales_amount'  => (float) $this->total_sales_amount,
            'total_reviews'       => (int) $this->total_reviews,
            'avg_reviews'         => (float) $this->avg_reviews,
            'total_followers'     => (int) $this->total_followers,
            'social_links'        => $this->profile_social_links ?? [],
            'created_at'          => $this->created_at ? $this->created_at->toISOString() : null,
        ];
    }
}

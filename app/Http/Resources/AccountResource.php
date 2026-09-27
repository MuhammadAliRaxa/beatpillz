<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => [
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'full_name' => $this->getName(),
            ],
            'username' => $this->username,
            'email' => $this->email,
            'balance' => $this->balance,
            'currency' => defaultCurrency()->code,
            'profile' => [
                'heading' => $this->profile_heading,
                'description' => $this->profile_description,
                'contact' => [
                    'email' => $this->profile_contact_email,
                ],
                'social_links' => $this->profile_social_links,
                'media' => [
                    'avatar' => $this->getAvatar(),
                    'cover' => $this->getProfileCover(),
                ],
            ],
            'registered_at' => $this->created_at,
        ];
    }
}
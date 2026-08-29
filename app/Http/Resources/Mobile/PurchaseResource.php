<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'purchase_code'     => $this->code,
            'license_type'      => $this->license_type,
            'license_name'      => $this->isLicenseTypeRegular() ? 'Regular License' : 'Extended License',
            'is_downloaded'     => (bool) $this->is_downloaded,
            'status'            => (int) $this->status,
            'support_expiry_at' => $this->support_expiry_at ? $this->support_expiry_at->toISOString() : null,
            'is_support_expired'=> $this->isSupportExpired(),
            'item'              => new ItemResource($this->item),
            'has_reviewed'      => $this->review !== null,
            'review'            => $this->review ? [
                'id'         => $this->review->id,
                'rating'     => (int) $this->review->rating,
                'subject'    => $this->review->subject,
                'body'       => $this->review->body,
                'created_at' => $this->review->created_at ? $this->review->created_at->toISOString() : null,
            ] : null,
            'created_at'        => $this->created_at ? $this->created_at->toISOString() : null,
        ];
    }
}

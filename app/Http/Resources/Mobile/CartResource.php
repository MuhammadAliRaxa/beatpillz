<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'license_type' => $this->license_type,
            'license_name' => $this->isLicenseTypeRegular() ? 'Regular License' : 'Extended License',
            'quantity'     => (int) $this->quantity,
            'total_amount' => (float) $this->getTotalAmount(),
            'item'         => new ItemResource($this->item),
            'created_at'   => $this->created_at ? $this->created_at->toISOString() : null,
        ];
    }
}

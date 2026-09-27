<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        $isRegular = $this->isLicenseTypeRegular();
        $unitPrice = $isRegular ? (float) $this->item->getRegularPrice() : (float) $this->item->getExtendedPrice();

        return [
            'id'           => $this->id,
            'license_type' => (int) $this->license_type,
            'license_name' => $isRegular ? 'Non-Exclusive (Regular)' : 'Exclusive (Extended)',
            'quantity'     => (int) $this->quantity,
            'unit_price'   => $unitPrice,
            'total_amount' => (float) $this->getTotalAmount(),
            'item'         => new ItemResource($this->item),
            'created_at'   => $this->created_at ? $this->created_at->toISOString() : null,
        ];
    }
}

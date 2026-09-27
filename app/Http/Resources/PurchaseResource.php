<?php

namespace App\Http\Resources;

use App\Http\Resources\ItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'purchase_code' => $this->code,
            'license_type' => $this->isLicenseTypeRegular() ? translate('Regular') : translate('Extended'),
            'price' => $this->sale->price,
            'currency' => defaultCurrency()->code,
            'item' => new ItemResource($this->item),
        ];

        if (@settings('item')->support_status && $this->support_expiry_at) {
            $data['supported_until'] = $this->support_expiry_at;
        }

        $data['downloaded'] = $this->isDownloaded() ? true : false;
        $data['date'] = $this->created_at;

        return $data;
    }
}
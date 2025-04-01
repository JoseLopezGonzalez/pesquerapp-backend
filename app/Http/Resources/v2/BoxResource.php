<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoxResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'palletId' => $this->pallet ? $this->pallet->id : null,
            'product' => [
                'species' => $this->product->species->toArrayAssoc(),
                'captureZone' => $this->product->captureZone->toArrayAssoc(),
                'articleGtin' => $this->product->article_gtin,
                'boxGtin' => $this->product->box_gtin,
                'palletGtin' => $this->product->pallet_gtin,
                'fixedWeight' => $this->product->fixed_weight,
                /* 'name' => $this->product->name, */
                'id' => $this->product->id,
            ],
            'lot' => $this->lot,
            'gs1128' => $this->gs1_128,
            'grossWeight' => $this->gross_weight,
            'netWeight' => $this->net_weight,
            'createdAt' => $this->created_at, //formatear para mostrar solo fecha
        ];
    }
}

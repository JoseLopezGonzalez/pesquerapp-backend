<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderPLannedProductDetailResource extends JsonResource
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
            'product' => $this->product->toArrayAssoc(),
            'quantity' => $this->quantity,
            'boxes' => $this->boxes,
            'tax' => $this->tax->toArrayAssoc(),
        ];
    }
}

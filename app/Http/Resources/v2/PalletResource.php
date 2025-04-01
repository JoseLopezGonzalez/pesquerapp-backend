<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PalletResource extends JsonResource
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
            'observations' => $this->observations,
            'state' => $this->palletState->name,
            'articlesNames' => $this->articlesNames,
            'boxes' => BoxResource::collection($this->boxes),
            'lots' => $this->lots,
            'netWeight' => $this->netWeight,
            'position' => $this->position,
            'store' => $this->store ? $this->store->name : null,
            'orderId' => $this->order_id,
            'numberOfBoxes' => $this->numberOfBoxes,
        ];
    }
}

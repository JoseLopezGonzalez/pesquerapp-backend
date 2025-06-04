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
            'boxes' => $this->boxes->map(function ($box) {
                return $box->toArrayAssoc();
            }),
            'lots' => $this->lots,
            'netWeight' => $this->netWeight !== null ? round($this->netWeight, 3) : null,
            'position' => $this->position,
            'store' => /* si es null o no */
                $this->store ? $this->store->id : null,
            'orderId' => $this->order_id,
            'numberOfBoxes' => $this->numberOfBoxes,
        ];
    }
}

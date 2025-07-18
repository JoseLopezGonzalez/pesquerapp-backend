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
            'state' => $this->palletState,
            'articlesNames' => $this->articlesNames,
            'boxes' => $this->boxes->map(function ($box) {
                return $box->toArrayAssocV2();
            }),
            'lots' => $this->lots,
            'netWeight' => $this->netWeight !== null ? round($this->netWeight, 3) : null,
            'position' => $this->position,
            'store' => /* si es null o no */
                $this->store ? [
                    'id' => $this->store->id,
                    'name' => $this->store->name,
                ] : null,
            'orderId' => $this->order_id,
            'numberOfBoxes' => $this->numberOfBoxes,
        ];
    }
}

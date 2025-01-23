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
            'article' => $this->article->toArrayAssoc(),
            'lot' => $this->lot,
            'gs1128' => $this->gs1_128,
            'grossWeight' => $this->gross_weight,
            'netWeight' => $this->net_weight,
            'palletId' => $this->pallet_id,
            'createdAt' => $this->created_at, //formatear para mostrar solo fecha
        ];
    }
}

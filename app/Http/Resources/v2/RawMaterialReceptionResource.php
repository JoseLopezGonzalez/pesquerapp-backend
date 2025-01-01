<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RawMaterialReceptionResource extends JsonResource
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
            'supplier' => $this->supplier,
            'date' => $this->date,
            'notes' => $this->notes,
            'netWeight' => $this->netWeight,
            'species' => $this->species,
            'details' => RawMaterialReceptionProductResource::collection($this->products) // Asumiendo que tienes un resource para RawMaterialReceptionProduct
        ];
    }


    
}

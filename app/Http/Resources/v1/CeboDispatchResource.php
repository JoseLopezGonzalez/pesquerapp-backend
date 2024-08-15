<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CeboDispatchResource extends JsonResource
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
            'supplier' => new SupplierResource($this->supplier), // Asumiendo que tienes un resource para Supplier
            'date' => $this->date,
            'notes' => $this->notes,
            'netWeight' => $this->netWeight,
            'details' => CeboDispatchProductResource::collection($this->products) // Asumiendo que tienes un resource para CeboDispatchProduct
        ];
    }
}

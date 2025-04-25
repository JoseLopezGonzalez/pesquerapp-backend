<?php

namespace App\Http\Resources\v1;

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
            'supplier' => new SupplierResource($this->supplier), // Asumiendo que tienes un resource para Supplier
            'date' => $this->date,
            'notes' => $this->notes,
            'declaredTotalAmount' => round((float) $this->declared_total_amount, 2),
            'declaredTotalNetWeight' => round((float) $this->declared_total_net_weight, 2),
            'netWeight' => round((float) $this->netWeight, 2),
            'totalAmount' => round((float) $this->total_amount, 2),
            'species' => $this->species,
            'details' => RawMaterialReceptionProductResource::collection($this->products) // Asumiendo que tienes un resource para RawMaterialReceptionProduct
        ];
    }



}

<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionResource extends JsonResource
{
     /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'lot' => $this->lot,
            'date' => $this->date,
            'species' => [
                'id' => $this->species->id,
                'name' => $this->species->name,
                'scientific_name' => $this->species->scientific_name,
                'fao' => $this->species->fao,
            ],
            'capture_zone' => [
                'id' => $this->captureZone->id,
                'name' => $this->captureZone->name,
            ],
            'notes' => $this->notes,
            'total_profit' => $this->total_profit,
            'total_profit_per_input_kg' => $this->total_profit_per_input_kg,
            'diagram_data' => $this->diagram_data,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

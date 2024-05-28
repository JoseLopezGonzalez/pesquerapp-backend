<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionNodeResource extends JsonResource
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
            'template_node_id' => $this->template_node_id,
            'parent_id' => $this->parent_id,
            'notes' => $this->notes,
            'articles' => $this->articles, // Puedes formatear esto según sea necesario
            'children' => ProductionNodeResource::collection($this->childrenRecursive),
        ];
    }
}

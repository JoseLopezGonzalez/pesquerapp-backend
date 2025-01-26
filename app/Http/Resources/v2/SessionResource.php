<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->tokenable_id,
            'user_name' => $this->tokenable?->name ?? 'Desconocido',
            'email' => $this->tokenable?->email ?? 'Desconocido',
            'last_used_at' => $this->last_used_at ? $this->last_used_at->format('Y-m-d H:i:s') : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'expires_at' => $this->expires_at ? $this->expires_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
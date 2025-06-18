<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'name' => $this->name,
            'type' => $this->type,
            'contactPerson' => $this->contact_person,
            'phone' => $this->phone,
            'emails' => $this->emailsArray,
            'ccEmails' => $this->ccEmailsArray,
            'address' => $this->address,
            'facilcomCode' => $this->facil_com_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

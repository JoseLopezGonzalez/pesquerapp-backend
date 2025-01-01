<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'customer' => $this->customer->toArrayAssoc(),
            'buyerReference' => $this->buyer_reference,
            'status' => $this->status,
            'loadDate' => $this->load_date,
            'salesperson' => $this->salesperson->toArrayAssoc(),
            'transport' => $this->transport->toArrayAssoc(),
            'numberOfPallets' => $this->numberOfPallets,
            'totalBoxes' => $this->totalBoxes,
            'incoterm' => $this->incoterm->toArrayAssoc(),
            'totalNetWeight' => $this->totalNetWeight,
            
            /* Antiguo */
            'paymentTerm' => $this->payment_term->toArrayAssoc(),
            'billingAddress' => $this->billing_address,
            'shippingAddress' => $this->shipping_address,
            'transportationNotes' => $this->transportation_notes,
            'productionNotes' => $this->production_notes,
            'accountingNotes' => $this->accounting_notes,
            'emails' => $this->emails,
            'hasPalletsOnStorage' => $this->hasPalletsOnStorage(),/* Posiblemente deprecado */
            'entryDate' => $this->entry_date,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}

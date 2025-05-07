<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'vat_number',
        'payment_term_id',
        'billing_address',
        'shipping_address',
        'transportation_notes',
        'production_notes',
        'accounting_notes',
        'salesperson_id',
        'emails',
        'contact_info',
        'country_id',
        'transport_id',
        'a3erp_code',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function salesperson()
    {
        return $this->belongsTo(Salesperson::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function transport()
    {
        return $this->belongsTo(Transport::class);
    }

    public function payment_term()
    {
        return $this->belongsTo(PaymentTerm::class);
    }

    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'alias' => $this->alias,
            'vatNumber' => $this->vat_number,
            'paymentTerm' => $this->payment_term->toArrayAssoc(),
            'billingAddress' => $this->billing_address,
            'shippingAddress' => $this->shipping_address,
            'transportationNotes' => $this->transportation_notes,
            'productionNotes' => $this->production_notes,
            'accountingNotes' => $this->accounting_notes,
            'salesperson' => $this->salesperson->toArrayAssoc(),
            'emails' => $this->emails,
            'contactInfo' => $this->contact_info,
            'country' => $this->country->toArrayAssoc(),
            'transport' => $this->transport->toArrayAssoc(),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }

    public function toArrayAssocShort()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'alias' => $this->alias,
            'vatNumber' => $this->vat_number,
            'billingAddress' => $this->billing_address,
        ];
    }

}

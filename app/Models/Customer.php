<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'vat_number', 'payment_term_id', 'billing_address', 'shipping_address', 'transportation_notes', 'production_notes', 'accounting_notes', 'salesperson_id', 'emails', 'contact_info', 'country_id', 'transport_id'];

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

}

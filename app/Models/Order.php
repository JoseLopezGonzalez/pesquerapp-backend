<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'payment_term_id', 'billing_address', 'shipping_address', 'transportation_notes', 'production_notes', 'accounting_notes', 'salesperson_id', 'emails', 'transport_id', 'entry_date', 'load_date', 'status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesperson()
    {
        return $this->belongsTo(Salesperson::class);
    }

    public function transport()
    {
        return $this->belongsTo(Transport::class);
    }

    public function pallets()
    {
        return $this->hasMany(Pallet::class);
    }

    public function payment_term()
    {
        return $this->belongsTo(PaymentTerm::class);
    }

    /* Order is active when status is 'finished' and loadDate is < now */
    public function isActive()
    {
        return $this->status == 'finished' && $this->load_date < now();
    }

   
}

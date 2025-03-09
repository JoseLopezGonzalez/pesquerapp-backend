<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'tax_id',
        'quantity',
        'pallets',
        'boxes',
        'unit_price',
        'discount_type',
        'discount_value',
        'line_base',
        'line_total',
    ];

    /**
     * Relación inversa: cada OrderDetail pertenece a un Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /* To array Assoc */
    public function toArrayAssoc()
    {
        return [
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'product_name' => $this->product->article->name,
            'tax_id' => $this->tax_id,
            'quantity' => $this->quantity,
            'pallets' => $this->pallets,
            'boxes' => $this->boxes,
            'unit_price' => $this->unit_price,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'line_base' => $this->line_base,
            'line_total' => $this->line_total,
        ];
    }

}

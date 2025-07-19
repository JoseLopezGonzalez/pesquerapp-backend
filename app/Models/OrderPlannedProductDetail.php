<?php

namespace App\Models;

use App\Traits\UsesTenantConnection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPlannedProductDetail  extends Model
{
    use UsesTenantConnection;
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'tax_id',
        'quantity',
        'boxes',
        'unit_price',
      /*   'line_base',
        'line_total', */
        /* 'pallets', */
        /* 'discount_type', */
        /* 'discount_value', */
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

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    /* To array Assoc */
    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'orderId' => $this->order_id,
            'product' => $this->product->toArrayAssoc(),
            'tax' => $this->tax->toArrayAssoc(),
            'quantity' => $this->quantity,
            'boxes' => $this->boxes,
            'unitPrice' => $this->unit_price,
           /*  'subTotal' => $this->line_base,
            'total' => $this->line_total, */
            /* 'pallets' => $this->pallets, */
            /* 'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value, */
        ];
    }

}

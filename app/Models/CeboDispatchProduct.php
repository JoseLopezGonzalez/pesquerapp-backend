<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CeboDispatchProduct extends Model
{
    use HasFactory;

    protected $fillable = ['dispatch_id', 'product_id', 'net_weight'];

    public function dispatch()
    {
        return $this->belongsTo(CeboDispatch::class, 'dispatch_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

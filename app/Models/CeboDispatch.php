<?php

// app/Models/CeboDispatch.php
namespace App\Models;

use App\Models\CeboDispatchProduct;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CeboDispatch extends Model
{
    use HasFactory;

    protected $fillable = ['supplier_id', 'date', 'notes', 'export_type'];

    protected $appends = ['net_weight', 'total_amount'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function products()
    {
        return $this->hasMany(CeboDispatchProduct::class, 'dispatch_id');
    }

    public function getNetWeightAttribute()
    {
        return $this->products->sum('net_weight');
    }

    public function getTotalAmountAttribute()
    {
        return $this->products->sum(function ($product) {
            return $product->net_weight * ($product->price ?? 0);
        });
    }
}

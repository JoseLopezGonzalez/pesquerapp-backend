<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialReceptionProduct extends Model
{
    use HasFactory;

    protected $fillable = ['reception_id', 'product_id', 'net_weight'];

    public function reception()
    {
        
        return $this->belongsTo(RawMaterialReception::class, 'reception_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /* Alias Attribute from RawMaterial atribute that coincida con el id de producto */
    public function getAliasAttribute()
    {
        return $this->product->rawMaterial->alias;
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialReceptionProduct extends Model
{
    use HasFactory;

    protected $fillable = ['reception_id', 'product_id', 'net_weight' , 'price'];

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
        /* Find RawMaterial(product_id) */
        $rawMaterial = RawMaterial::where('id', $this->product_id)->first();
        return $rawMaterial->alias;
        /* return $this->product->rawMaterials->where('id', $this->product_id)->first()->alias; */
    }

}

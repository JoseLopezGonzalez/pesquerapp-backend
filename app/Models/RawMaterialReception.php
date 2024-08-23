<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialReception extends Model
{
    use HasFactory;

    protected $fillable = ['supplier_id', 'date', 'notes'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function products()
    {
        return $this->hasMany(RawMaterialReceptionProduct::class , 'reception_id');
    }

    public function getNetWeightAttribute()
    {
        return $this->products->sum('net_weight');
    }


    /* GEnerar atributo especie segun la especie a la que pertenezca sus productos */
    public function getSpecieAttribute()
    {
        return $this->products->first()->product->article->specie;
    }
}

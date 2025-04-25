<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialReception extends Model
{
    use HasFactory;

    protected $fillable = ['supplier_id', 'date', 'notes', 'declared_total_amount', 'declared_total_net_weight'];

    protected $appends = ['total_amount'];

    /* hacer numeros  declared_total_amount y declared_total_net_weight*/
    protected $casts = [
        'declared_total_amount' => 'decimal:2',
        'declared_total_net_weight' => 'decimal:2',
        'date' => 'datetime:Y-m-d',
    ];


    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function products()
    {
        return $this->hasMany(RawMaterialReceptionProduct::class, 'reception_id');
    }

    public function getNetWeightAttribute()
    {
        return $this->products->sum('net_weight');
    }


    /* GEnerar atributo especie segun la especie a la que pertenezca sus productos */
    public function getSpeciesAttribute()
    {
        return $this->products->first()->product->species;
    }

    public function getTotalAmountAttribute()
    {
        return $this->products->sum(function ($product) {
            return ($product->net_weight ?? 0) * ($product->price ?? 0);
        });
    }

}

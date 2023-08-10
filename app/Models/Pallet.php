<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pallet extends Model
{
    use HasFactory;

    protected $fillable = ['observaciones', 'id_estado', 'id_almacen'];

    public function palletState()
    {
        return $this->belongsTo(PalletState::class, 'id_estado');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'id_almacen');
    }

    public function boxes()
    {
        return $this->hasMany(Box::class, 'id_palet');
    }

    //Accessor
    public function getPesoNetoAttribute()
    {
        $pesoNeto = 0;
        foreach($this->boxes as $box){
            $pesoNeto += $box->peso_neto;
        }
        return $pesoNeto;
    }

    public function toArrayAssoc()
    {

        return [
            'id' => $this->id,
            'observaciones' => $this->observaciones,
            'estado' => $this->palletState->toArrayAssoc(),
            'idAlmacen' => $this->id_almacen,
            'boxes' => $this->boxes->map(function ($box) {
                return $box->toArrayAssoc();
            }),
        ];
    }


    
}

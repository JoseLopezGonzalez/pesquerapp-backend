<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Palet extends Model
{
    use HasFactory;

    protected $fillable = ['observaciones', 'id_estado', 'id_almacen'];

    public function estadoPalet()
    {
        return $this->belongsTo(EstadoPalet::class, 'id_estado');
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen');
    }

    public function cajas()
    {
        return $this->hasMany(Caja::class, 'id_palet');
    }

    //Accessor
    public function getPesoNetoAttribute()
    {
        $pesoNeto = 0;
        foreach($this->cajas as $caja){
            $pesoNeto += $caja->PesoNeto;
        }
        return $pesoNeto;
    }

    public function toArrayAssoc()
    {

        return [
            'id' => $this->id,
            'observaciones' => $this->observaciones,
            'estado' => $this->estadoPalet->toArrayAssoc(),
            'idAlmacen' => $this->idAlmacen,
        ];
    }


    
}

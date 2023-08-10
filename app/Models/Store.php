<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Pallet;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'id_categoria'];
    //protected $table = 'stores';

    public function categoria()
    {
        return $this->belongsTo(ArticleCategory::class, 'id_categoria');
    }

    // Definir relación con Pallet
    public function pallets()
    {
        return $this->hasMany(Pallet::class, 'id_almacen');
    }

    //Accessor 
    public function getPesoNetoPaletsAttribute()
    {
        $pesoNetoPalets = 0;
        foreach ($this->pallets as $pallet) {
            $pesoNetoPalets += $pallet->pesoNeto; //Implementar atributo accesor en pallet pesoNeto
        }
        return $pesoNetoPalets;
    }

    //Accessor 
    public function getPesoNetoCajasAttribute()
    {
        //Implementar...
        return 0;
    }

    //Accessor 
    public function getPesoNetoTinasAttribute()
    {
        //Implementar...
        return 0;
    }

    public function getPesoNetoTotalAttribute() //No se bien si llamarlo simplemente pesoNeto
    {
        return $this->pesoNetoPalets + $this->pesoNetoTinas + $this->pesoNetoCajas;
    }

    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'temperatura' => $this->temperatura,
            'capacidad' => $this->capacidad,
            'pesoNetoPalets' => $this->pesoNetoPalets,
            'pesoNetoTotal' => $this->pesoNetoTotal,
            'pallets' => $this->pallets->map(function ($pallet) {
                return $pallet->toArrayAssoc();
            }),

        ];
    }
}

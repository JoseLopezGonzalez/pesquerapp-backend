<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Palet;

class Almacen extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'id_categoria'];
    protected $table = 'almacenes';

    public function categoria()
    {
        return $this->belongsTo(CategoriaArticulo::class, 'id_categoria');
    }

    // Definir relación con Palet
    public function palets()
    {
        return $this->hasMany(Palet::class, 'id_almacen');
    }

    //Accessor 
    public function getPesoNetoPaletsAttribute()
    {
        $pesoNetoPalets = 0;
        foreach ($this->palets as $palet) {
            $pesoNetoPalets += $palet->pesoNeto; //Implementar atributo accesor en palet pesoNeto
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
            'palets' => $this->palets->map(function ($palet) {
                return $palet->toArrayAssoc();
            }),

        ];
    }
}

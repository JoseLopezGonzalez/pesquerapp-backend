<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Articulo;

class ArticuloMateriaPrima extends Model
{
    use HasFactory;

    public function articulo()
    {
        return $this->belongsTo(Articulo::class); // No se bien porque no indica que el id es el que relaciona las tablas
    }

    public function especie()
    {
        return $this->belongsTo(Articulo::class , 'id_especie'); // No se bien porque no indica que el id es el que relaciona las tablas
    }

    public function zonaCaptura()
    {
        return $this->belongsTo(Articulo::class , 'id_zona_captura'); // No se bien porque no indica que el id es el que relaciona las tablas
    }

    public function toArrayAssoc()
    {
        return array_merge($this->articulo->toArrayAssoc() , [
            'especie' => $this->especie->toArrayAssoc(),
            'zonaCaptura' => $this->zonaCaptura->toArrayAssoc(),
            'gtin' => $this->gtin,
            'gtinCaja' => $this->gtinCaja,
            'gtinPalet' => $this->gtinPalet,
            'pesoFijo' => $this->pesoFijo,
        ]);

    }
}

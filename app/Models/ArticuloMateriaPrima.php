<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Articulo;
use App\Models\Especie;
use App\Models\ZonaCaptura;

class ArticuloMateriaPrima extends Model
{
    use HasFactory;

    protected $table = 'articulos_materia_prima';

    public function articulo()
    {
        return $this->belongsTo(Articulo::class , 'id'); // No se bien porque no indica que el id es el que relaciona las tablas
    }

    public function especie()
    {
        return $this->belongsTo(Especie::class , 'id_especie'); // No se bien porque no indica que el id es el que relaciona las tablas
    }

    public function zonaCaptura()
    {
        return $this->belongsTo(ZonaCaptura::class , 'id_zona_captura'); // No se bien porque no indica que el id es el que relaciona las tablas
    }

    public function toArrayAssoc()
    {
        return array_merge($this->articulo->toArrayAssoc() , [
            'especie' => $this->especie->toArrayAssoc(),
            'zonaCaptura' => $this->zonaCaptura->toArrayAssoc(),
            'gtin' => $this->GTIN,
            'gtinCaja' => $this->GTIN_caja,
            'gtinPalet' => $this->GTIN_palet,
            'pesoFijo' => $this->peso_fijo,
        ]);

    }
}

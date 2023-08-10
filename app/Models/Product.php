<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Article;
use App\Models\Species;
use App\Models\CaptureZone;

class Product extends Model
{
    use HasFactory;

    //protected $table = 'products';

    public function article()
    {
        return $this->belongsTo(Article::class , 'id'); // No se bien porque no indica que el id es el que relaciona las tablas
    }

    public function species()
    {
        return $this->belongsTo(Species::class , 'id_especie'); // No se bien porque no indica que el id es el que relaciona las tablas
    }

    public function captureZone()
    {
        return $this->belongsTo(CaptureZone::class , 'id_zona_captura'); // No se bien porque no indica que el id es el que relaciona las tablas
    }

    public function toArrayAssoc()
    {
        return array_merge($this->article->toArrayAssoc() , [
            'species' => $this->species->toArrayAssoc(),
            'captureZone' => $this->captureZone->toArrayAssoc(),
            'gtin' => $this->GTIN,
            'gtinCaja' => $this->GTIN_caja,
            'gtinPalet' => $this->GTIN_palet,
            'pesoFijo' => $this->peso_fijo,
        ]);

    }
}

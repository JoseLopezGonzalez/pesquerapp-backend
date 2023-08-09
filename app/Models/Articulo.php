<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ArticuloMateriaPrima;
use App\Models\CategoriaArticulo;

class Articulo extends Model
{
    use HasFactory;
    protected $table = 'articulos';

    public function articuloMateriaPrima()
    {
        if ($this->categoria->id == 1) { // Cambia el valor 1 por el ID de la categoría correspondiente
            return $this->hasOne(ArticuloMateriaPrima::class);
        }
        return null; // No hay relación
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaArticulo::class , 'id_categoria');
    }

    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'categoria' => $this->categoria->toArrayAssoc(),
        ];
    }
}

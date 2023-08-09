<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaArticulo extends Model
{
    use HasFactory;
    
    protected $fillable = ['nombre'];
    protected $table = 'categorias_articulos';

    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'id_categoria');
    }

    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
        ];
    }

}

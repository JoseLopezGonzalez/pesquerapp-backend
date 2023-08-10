<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Product;
use App\Models\ArticleCategory;

class Article extends Model
{
    use HasFactory;
    //protected $table = 'articles';

    public function product()
    {
        if ($this->categoria->nombre === 'product') { // Cambia el valor 1 por el ID de la categoría correspondiente
            return $this->hasOne(Product::class);
        }
        return null; // No hay relación
    }

    public function categoria()
    {
        return $this->belongsTo(ArticleCategory::class , 'id_categoria');
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
    use HasFactory;
    
    protected $fillable = ['nombre'];
    protected $table = 'article_categories';

    public function articles()
    {
        return $this->hasMany(Article::class, 'id_categoria');
    }

    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
        ];
    }

}

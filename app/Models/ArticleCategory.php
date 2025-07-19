<?php

namespace App\Models;

use App\Traits\UsesTenantConnection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
    use UsesTenantConnection;
    use HasFactory;
    
    protected $fillable = ['name'];
    protected $table = 'article_categories';

    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id');
    }

    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

}

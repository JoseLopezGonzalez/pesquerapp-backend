<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionNode extends Model
{
    use HasFactory;

    protected $fillable = ['production_id', 'template_node_id', 'parent_id', 'notes'];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function templateNode()
    {
        return $this->belongsTo(TemplateNode::class);
    }

    public function children()
    {
        return $this->hasMany(ProductionNode::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(ProductionNode::class, 'parent_id');
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'production_node_article')->withPivot('quantity');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}

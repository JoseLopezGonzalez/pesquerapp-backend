<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'species_id'];

    public function templateNodes()
    {
        return $this->hasMany(TemplateNode::class);
    }

    public function species()
    {
        return $this->belongsTo(Species::class);
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'process_article');
    }
}

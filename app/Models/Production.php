<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function nodes()
    {
        return $this->hasMany(ProductionNode::class);
    }

    public function getTreeAttribute()
    {
        $nodes = $this->nodes()->whereNull('parent_id')->with('childrenRecursive')->get();
        return $nodes;
    }
}

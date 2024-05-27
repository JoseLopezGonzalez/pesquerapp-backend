<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateNode extends Model
{
    use HasFactory;

    protected $fillable = ['process_id', 'parent_id'];

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function children()
    {
        return $this->hasMany(TemplateNode::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(TemplateNode::class, 'parent_id');
    }
}

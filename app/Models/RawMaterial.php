<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'fixed',
        'alias',
        'created_at',
        'updated_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class , 'id');
    }

    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'name' => $this->product->article->name,
            'alias' => $this->alias,
            'fixed' => $this->fixed,
            'product' => $this->product->toArrayAssoc(),
        ];
    }
}

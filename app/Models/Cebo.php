<?php

namespace App\Models;

use App\Traits\UsesTenantConnection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cebo extends Model
{
    use UsesTenantConnection;
    use HasFactory;

    protected $fillable = [
        'id',
        'fixed',
        'created_at',
        'updated_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id');
    }

    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'name' => $this->product->article->name,
            'fixed' => $this->fixed,
            'product' => $this->product->toArrayAssoc(),
        ];
    }
}

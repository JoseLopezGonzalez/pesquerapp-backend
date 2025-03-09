<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rate',
    ];

    /* To array Assoc */
    public function getToArrayAssocAttribute()
    {
        return [
            'name' => $this->name,
            'rate' => $this->rate,
        ];
    }

    /**
     * Relación inversa: cada Tax pertenece a muchos OrderDetail.
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

}


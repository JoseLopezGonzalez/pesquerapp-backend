<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;

    protected $fillable = [
        'lot',
        'date',
        'species_id',
        'capture_zone_id',
        'notes',
        'diagram_data',
    ];

    protected $casts = [
        'diagram_data' => 'array', // Casteo para manipular JSON como array
    ];
}


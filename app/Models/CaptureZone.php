<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaptureZone extends Model
{
    use HasFactory;
    protected $table = 'capture_zones';

    protected $fillable = ['nombre'];

    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
        ];
    }
}

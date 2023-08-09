<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZonaCaptura extends Model
{
    use HasFactory;
    protected $table = 'zonas_captura';

    protected $fillable = ['nombre'];

    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
        ];
    }
}

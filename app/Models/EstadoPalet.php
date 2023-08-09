<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoPalet extends Model
{
    use HasFactory;
    protected $table = 'estados_palets';

    protected $fillable = ['nombre'];
    
    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
        ];
    }

}

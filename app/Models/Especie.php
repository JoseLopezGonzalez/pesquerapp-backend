<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
    use HasFactory;
    
    protected $fillable = ['nombre', 'nombre_cientifico', 'fao', 'imagen'];

    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'nombreCientifico' => $this->nombre_cientifico,
            'fao' => $this->fao,
            'imagen' => $this->imagen,
        ];
    }


}

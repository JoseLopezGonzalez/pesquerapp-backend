<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FishingGear extends Model
{
    use HasFactory;

    /* Clave foranea fishing_gear - Actualización nueva  en Tabla Species*/
    /* $table->unsignedBigInteger('fishing_gear_id'); */

    public function species()
    {
        return $this->hasMany(Species::class);
    }
}

<?php

namespace App\Models;

use App\Traits\UsesTenantConnection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Species extends Model
{
    use UsesTenantConnection;
    use HasFactory;
    
    protected $fillable = ['name', 'scientific_name', 'fao', 'image' , 'fishing_gear_id'];

    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'scientificName' => $this->scientific_name,
            'fao' => $this->fao,
            'image' => $this->image,
        ];
    }

    /* Clave foranea fishing_gear - Actualización nueva */
    /* $table->unsignedBigInteger('fishing_gear_id'); */
    public function fishingGear()
    {
        return $this->belongsTo(FishingGear::class , 'fishing_gear_id');
    }

    // Relación con el modelo Production
    public function productions()
    {
        return $this->hasMany(Production::class, 'species_id');
    }


}

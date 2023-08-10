<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Pallet;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id'];
    //protected $table = 'stores';

    public function categoria()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    // Definir relación con Pallet
    public function pallets()
    {
        return $this->hasMany(Pallet::class, 'store_id');
    }

    //Accessor 
    public function getNetWeightPalletsAttribute()
    {
        $netWeightPallets = 0;
        foreach ($this->pallets as $pallet) {
            $netWeightPallets += $pallet->netWeight; //Implementar atributo accesor en pallet pesoNeto
        }
        return $netWeightPallets;
    }

    //Accessor 
    public function getNetWeightBoxesAttribute()
    {
        //Implementar...
        return 0;
    }

    //Accessor 
    public function getNetWeightBigBoxesAttribute()
    {
        //Implementar...
        return 0;
    }

    public function getTotalNetWeightAttribute() //No se bien si llamarlo simplemente pesoNeto
    {
        return $this->netWeightPallets + $this->netWeightBigBoxes + $this->netWeightBoxes;
    }

    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'temperature' => $this->temperature,
            'capacity' => $this->capacity,
            'netWeightPallets' => $this->netWeightPallets,
            'totalNetWeight' => $this->totalNetWeight,
            'pallets' => $this->pallets->map(function ($pallet) {
                return $pallet->toArrayAssoc();
            }),
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pallet extends Model
{
    use HasFactory;

    protected $fillable = ['observations', 'state_id', 'store_id'];

    public function palletState()
    {
        return $this->belongsTo(PalletState::class, 'state_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function boxes()
    {
        return $this->hasMany(Box::class, 'pallet_id');
    }

    //Accessor
    public function getNetWeightAttribute()
    {
        $netWeight = 0;
        foreach($this->boxes as $box){
            $netWeight += $box->net_weight;
        }
        return $netWeight;
    }

    public function toArrayAssoc()
    {

        return [
            'id' => $this->id,
            'observations' => $this->observations,
            'state' => $this->palletState->toArrayAssoc(),
            'storeId' => $this->store_id,
            'boxes' => $this->boxes->map(function ($box) {
                return $box->toArrayAssoc();
            }),
        ];
    }


    
}

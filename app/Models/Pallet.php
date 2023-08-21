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

   /* public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    } */

    public function boxes()
    {
        return $this->hasMany(PalletBox::class, 'pallet_id');
    }

    //Accessor
    public function getNetWeightAttribute()
    {
        /* $netWeight = 0;
        $this->boxes->map(function ($box) {
            global $netWeight;
            var_dump($box->net_weight);
            $netWeight += $box->net_weight;
        });
        return $netWeight; */
        //dd($this->boxes);
        return $this->boxes->reduce(function ($carry, $box) {
            return $carry + $box->net_weight;
        }, 0);
    }

    public function getPositionAttribute()
    {
       $pallet = StoredPallet::where('pallet_id', $this->id)->first();

       if($pallet)
       {
           return $pallet->position;
       } else{
              return null;
       }
    }

    public function toArrayAssoc()
    {

        return [
            'id' => $this->id,
            'observations' => $this->observations,
            'state' => $this->palletState->toArrayAssoc(),
            'boxes' => $this->boxes->map(function ($box) {
                return $box->toArrayAssoc();
            }),
            'netWeight' => $this->netWeight,
        ];
    }

    public function getStoreAttribute()
    {
        $pallet = StoredPallet::where('pallet_id', $this->id)->first();

        var_dump($pallet);
        if($pallet)
        {
            return $pallet->store;
        } else{
               return null;
        }
    }
    


    
}

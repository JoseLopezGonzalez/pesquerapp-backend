<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Pallet;

class StoredPallet extends Model
{
    use HasFactory;
    //protected $table = 'pallet_positions_store';

    public function pallet()
    {
        return $this->belongsTo(Pallet::class, 'pallet_id');
    }

    public function toArrayAssoc()
    {

        return array_merge($this->pallet->toArrayAssoc(), [
            //'storeId' => $this->store_id,
            'position' => $this->position,
        ]);
    }
}

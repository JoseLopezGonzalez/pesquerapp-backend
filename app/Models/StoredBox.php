<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Pallet;

class StoredPallet extends Model
{
    use HasFactory;
    //protected $table = 'pallet_positions_store';

    public function box()
    {
        return $this->belongsTo(Box::class, 'box_id');
    }

    public function toArrayAssoc()
    {

        return array_merge($this->pallet->toArrayAssoc(), [
            //'storeId' => $this->store_id,
            'position' => $this->position,
        ]);
    }
}

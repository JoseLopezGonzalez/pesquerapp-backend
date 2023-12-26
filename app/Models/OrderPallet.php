<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'pallet_id',
    ];

    public function toArrayAssoc(){
        return [
            'id' => $this->id,
            'orderId' => $this->order_id,
            'palletId' => $this->pallet_id,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}

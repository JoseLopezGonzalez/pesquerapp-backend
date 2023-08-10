<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PalletBox extends Model
{
    use HasFactory;

    public function box()
    {
        return $this->belongsTo(Box::class, 'box_id');
    }

    public function toArrayAssoc()
    {
        return $this->box->toArrayAssoc();
    }
}

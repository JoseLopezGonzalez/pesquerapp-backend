<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CeboDispatch extends Model
{
    use HasFactory;

    protected $fillable = ['supplier_id', 'date', 'notes'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function products()
    {
        return $this->hasMany(CeboDispatchProduct::class, 'dispatch_id');
    }

    public function getNetWeightAttribute()
    {
        return $this->products->sum('net_weight');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    use HasFactory;
    //protected $table = 'boxes';

    protected $fillable = [ 'article_id', 'lot', 'gs1_128', 'gross_weight', 'net_weight'];

    //Alguna parte del codigo usa esto todavia aunque este mal semanticamente
    public function article()
    {
        return $this->belongsTo(Product::class, 'article_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'article_id');
    }

    public function palletBox()
    {
        return $this->hasOne(PalletBox::class, 'box_id');
    }

    public function getPalletAttribute()
    {
        
        return $this->palletBox->pallet;
    }

    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'palletId' => $this->pallet_id,
            'article' => $this->article->toArrayAssoc(),
            'lot' => $this->lot,
            'gs1128' => $this->gs1_128,
            'grossWeight' => $this->gross_weight,
            'netWeight' => $this->net_weight,
            'createdAt' => $this->created_at, //formatear para mostrar solo fecha
        ];
    }
}

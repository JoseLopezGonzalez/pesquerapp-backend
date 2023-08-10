<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    use HasFactory;
    //protected $table = 'boxes';

    protected $fillable = ['id_palet', 'id_articulo', 'lote', 'GS1_128', 'peso_bruto', 'peso_neto'];

    public function article()
    {
        return $this->belongsTo(Product::class, 'id_articulo');
    }

    public function pallet()
    {
        return $this->belongsTo(Pallet::class, 'id_palet');
    }

    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'idPalet' => $this->id_palet,
            'article' => $this->article->toArrayAssoc(),
            'lote' => $this->lote,
            'gs1128' => $this->GS1_128,
            'pesoBruto' => $this->peso_bruto,
            'pesoNeto' => $this->peso_neto,
        ];
    }
}

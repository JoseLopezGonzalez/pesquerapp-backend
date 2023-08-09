<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;
    protected $table = 'cajas';

    protected $fillable = ['id_palet', 'id_articulo', 'lote', 'GS1_128', 'peso_bruto', 'peso_neto'];

    public function articulo()
    {
        return $this->belongsTo(ArticuloMateriaPrima::class, 'id_articulo');
    }

    public function palet()
    {
        return $this->belongsTo(Palet::class, 'id_palet');
    }

    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'idPalet' => $this->id_palet,
            'articulo' => $this->articulo->toArrayAssoc(),
            'lote' => $this->lote,
            'gs1128' => $this->GS1_128,
            'pesoBruto' => $this->peso_bruto,
            'pesoNeto' => $this->peso_neto,
        ];
    }
}

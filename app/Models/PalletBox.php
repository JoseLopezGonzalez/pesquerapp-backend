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

    public function getNetWeightAttribute(){
        return $this->box->net_weight;
    }

    public function delete()
{
    // Personaliza aquí la lógica de eliminación que deseas realizar antes de la eliminación real
    // Por ejemplo, puedes desencadenar eventos, eliminar relaciones, etc.
    $this->box->delete();


    // Llama al método de eliminación del padre (Eloquent)
    parent::delete();
}
}

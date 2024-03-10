<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    use HasFactory;
    //protected $table = 'boxes';

    protected $fillable = ['pallet_id', 'article_id', 'lot', 'gs1_128', 'gross_weight', 'net_weight'];

    public function article()
    {
        return $this->belongsTo(Product::class, 'article_id');
    }

    public function pallet()
    {

        //necesito recuperaar el pallet_id de la tabla pallet_boxes donde aparezca el id en box_id
        $pallet_id= PalletBox::where('box_id', $this->id);
        //hacer que se mueestre por pantalla pallet_id y que se termine la ejecucion
        echo $pallet_id;
        die();
        /* return $this->belongsToMany(Pallet::class, 'pallet_boxes', 'box_id', 'pallet_id'); */





        //El pallet_id esta en la tabla pallet_boxes y no en la tabla boxes. Ademas solu puede tener un palet






        /* return $this->belongsTo(Pallet::class, 'pallet_id'); */
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

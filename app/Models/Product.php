<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Article;
use App\Models\Species;
use App\Models\CaptureZone;

class Product extends Model
{
    use HasFactory;

    //protected $table = 'products';
    /* fillable */
    protected $fillable = [
        'id',
        'article_id',
        'species_id',
        'capture_zone_id',
        'article_gtin',
        'box_gtin',
        'pallet_gtin',
        'fixed_weight',
        'name',
        'a3erp_code',
    ];

   /*  public function article()
    {
        return $this->belongsTo(Article::class, 'id'); // No se bien porque no indica que el id es el que relaciona las tablas
    } */

    public function species()
    {
        return $this->belongsTo(Species::class, 'species_id'); // No se bien porque no indica que el id es el que relaciona las tablas
    }

    public function captureZone()
    {
        return $this->belongsTo(CaptureZone::class, 'capture_zone_id'); // No se bien porque no indica que el id es el que relaciona las tablas
    }

    public function toArrayAssoc()
    {
        return array_merge($this->article->toArrayAssoc(), [
            'species' => $this->species->toArrayAssoc(),
            'captureZone' => $this->captureZone->toArrayAssoc(),
            'articleGtin' => $this->article_gtin,
            'boxGtin' => $this->box_gtin,
            'palletGtin' => $this->pallet_gtin,
            'fixedWeight' => $this->fixed_weight,
            'name' => $this->name,
            'id' => $this->id,
        ]);

    }

    /* name attribute */
    public function getNameAttribute()
    {
        return $this->article->name;
    }

    public function productionNodes()
    {
        return $this->belongsToMany(ProductionNode::class, 'production_node_product')->withPivot('quantity');
    }

    public function rawMaterials()
    {
        return $this->has(RawMaterial::class, 'id');
    }


    public function article()
    {
        return $this->hasOne(Article::class, 'id', 'id');
    }




}

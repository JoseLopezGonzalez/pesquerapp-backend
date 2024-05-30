<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\RawMaterialResource;
use App\Models\RawMaterial;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    public function index(Request $request)
    {

        $query = RawMaterial::query();

        if ($request->has('species') && $request->has('species.id')) {
            /* Raw Material */
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('species_id', $request->species['id']);
            });
        }

        $rawMaterials = $query->get();

        return RawMaterialResource::collection($rawMaterials);



        /* $products = $query->get();

        $sortedProducts = $products->sortBy(function ($product) {
            return $product->article->name;
        });

        return ProductResource::collection($sortedProducts); */

    }
}

<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ProductResource;
use App\Http\Resources\v2\ProductResource as V2ProductResource;
use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();
        /* Add article */
        $query->with('article');

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('ids')) {
            $query->whereIn('id', $request->ids);
        }

        /*name but product.article.name  */
        if ($request->has('name')) {
            $query->whereHas('article', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            });
        }

        /* species Where in*/
        if ($request->has('species')) {
            $query->whereIn('species_id', $request->species);
        }

        /* capture zone where in*/
        if ($request->has('captureZones')) {
            $query->whereIn('capture_zone_id', $request->captureZones);
        }

        /* articleGtin */
        if ($request->has('articleGtin')) {
                $query->where('article_gtin', $request->articleGtin);
        }

        /* boxGtin */
        if ($request->has('boxGtin')) {
            $query->where('box_gtin', $request->articleGtin);
        }

        /* palletGtin */
        if ($request->has('palletGtin')) {
                $query->where('pallet_gtin', $request->palletGtin);
        }
        

        /* Always order by article.name */
        $query->orderBy(
            Article::select('name')
                ->whereColumn('articles.id', 'products.id'),
            'asc'
        );


        $perPage = $request->input('perPage', 14); // Default a 10 si no se proporciona
        return V2ProductResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get all options for the products select box.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        /* Ojo que product no tiene name, teiene article que a su vex tiene name */

        $products = Product::join('articles', 'products.id', '=', 'articles.id')
            ->select('products.id', 'articles.name') // Selecciona los campos necesarios
            ->orderBy('articles.name', 'asc') // Ordena por el nombre del artículo
            ->get();


        return response()->json($products);
    }
}

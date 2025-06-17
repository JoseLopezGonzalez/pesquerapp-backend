<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v2\ProductResource;
use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return ProductResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'speciesId' => 'required|exists:species,id',
            'captureZoneId' => 'required|exists:capture_zones,id',
            'articleGtin' => 'nullable|string|regex:/^[0-9]{8,14}$/',
            'boxGtin' => 'nullable|string|regex:/^[0-9]{8,14}$/',
            'palletGtin' => 'nullable|string|regex:/^[0-9]{8,14}$/',
        ]);

        $product = DB::transaction(function () use ($validated) {
            // 1. Crear el artículo
            $article = Article::create([
                'name' => $validated['name'],
                'category_id' => 1, // Asegúrate de que esta categoría exista
            ]);

            // 2. Crear el producto con el mismo ID
            $product = Product::create([
                'id' => $article->id,
                'species_id' => $validated['speciesId'],
                'capture_zone_id' => $validated['captureZoneId'],
                'article_gtin' => $validated['articleGtin'] ?? null,
                'box_gtin' => $validated['boxGtin'] ?? null,
                'pallet_gtin' => $validated['palletGtin'] ?? null,
            ]);

            return $product;
        });

        // 3. Cargar las relaciones necesarias para el resource
        $product->loadMissing(['article', 'species', 'captureZone']);

        return response()->json([
            'message' => 'Producto creado con éxito',
            'data' => new ProductResource($product),
        ], 201);
    }





    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

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
        DB::transaction(function () use ($id) {
            $product = Product::findOrFail($id);
            $product->delete();

            Article::where('id', $id)->delete();
        });

        return response()->json(['message' => 'Producto eliminado correctamente']);
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => 'No se proporcionaron IDs válidos'], 400);
        }

        DB::transaction(function () use ($ids) {
            Product::whereIn('id', $ids)->delete();
            Article::whereIn('id', $ids)->delete();
        });

        return response()->json(['message' => 'Productos eliminados correctamente']);
    }



    /**
     * Get all options for the products select box.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        /* Ojo que product no tiene name, teiene article que a su vex tiene name */
        /* box_gtin */
        $products = Product::join('articles', 'products.id', '=', 'articles.id')
            ->select('products.id', 'articles.name', 'products.box_gtin as boxGtin') // Selecciona los campos necesarios
            ->orderBy('articles.name', 'asc') // Ordena por el nombre del artículo
            ->get();


        return response()->json($products);
    }
}

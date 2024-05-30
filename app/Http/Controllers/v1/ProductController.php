<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /* Sort by A-Z Product->article->name*/
        /* Products tiene un atributo id que es clave foranea del id de articles donde se encuentra el atributo name */

        $query = Product::query();

        if ($request->has('species')) {
            $query->where('species_id', $request->species);
        }

        $products = $query->get();

        $sortedProducts = $products->sortBy(function ($product) {
            return $product->article->name;
        });

        

        /* $products = Product::with(['article'])->get(); // Load products and their corresponding articles

        // Sort products based on the article name
        $sortedProducts = $products->sortBy(function ($product) {
            return $product->article->name;
        }); */

        return ProductResource::collection($sortedProducts);

        /* return ProductResource::collection(Product::orderBy('name')->get()); */
        /*  return ProductResource::collection(Product::all()); */
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
    public function show(string $id)
    {
        return new ProductResource(Product::find($id));
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
        //
    }
}

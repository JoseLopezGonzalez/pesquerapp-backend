<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\RawMaterialReceptionResource;
use App\Models\RawMaterialReception;
use Illuminate\Http\Request;

class RawMaterialReceptionController extends Controller
{
    public function index()
    {
        $receptions = RawMaterialReception::with('supplier', 'products.product')->get();
        return RawMaterialReceptionResource::collection($receptions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            /* Float */
            'products.*.net_weight' => 'required|numeric',
        ]);

        $reception = RawMaterialReception::create($validated);

        foreach ($validated['products'] as $product) {
            $reception->products()->create($product);
        }

        return new RawMaterialReceptionResource($reception);
    }

    public function show($id)
    {
        $reception = RawMaterialReception::with('supplier', 'products.product')->findOrFail($id);
        return new RawMaterialReceptionResource($reception);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'supplier_id' => 'sometimes|required|exists:suppliers,id',
            'date' => 'sometimes|required|date',
            'notes' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*.product_id' => 'required_with:products|exists:products,id',
            'products.*.quantity' => 'required_with:products|integer',
            'products.*.unit' => 'required_with:products|string|max:10'
        ]);

        $reception = RawMaterialReception::findOrFail($id);
        $reception->update($validated);

        if ($request->has('products')) {
            $reception->products()->delete();
            foreach ($validated['products'] as $product) {
                $reception->products()->create($product);
            }
        }

        return new RawMaterialReceptionResource($reception);
    }

    public function destroy($id)
    {
        $reception = RawMaterialReception::findOrFail($id);
        $reception->delete();
        return response()->json(null, 204);
    }
}

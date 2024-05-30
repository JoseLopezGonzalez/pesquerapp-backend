<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\RawMaterialReceptionResource;
use App\Models\RawMaterialReception;
use Illuminate\Support\Facades\Validator;
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

        $validator = Validator::make($request->all(), [
            'suplier.id' => 'required',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.netWeight' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Código de estado 422 - Unprocessable Entity
        }

        $reception = new RawMaterialReception();
        $reception->supplier_id = $request->supplier->id;
        $reception->date = $request->date;
        
        if($request->has('notes')){
            $reception->notes = $request->notes;
        }

        if($request->has('products')){
            foreach($request->products as $product){
                $reception->products()->create([
                    'product_id' => $product->id,
                    'net_weight' => $product->netWeight
                ]);
            }
        }

        $reception->save();

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
            'products.*.id' => 'required_with:products|exists:products,id',
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

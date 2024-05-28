<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ProductionResource;
use App\Models\Production;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = Production::all();
        return ProductionResource::collection($productions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $production = Production::create($validated);
        return new ProductionResource($production);
    }

    public function show($id)
    {
        $production = Production::findOrFail($id);
        return new ProductionResource($production);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255'
        ]);

        $production = Production::findOrFail($id);
        $production->update($validated);
        return new ProductionResource($production);
    }

    public function destroy($id)
    {
        $production = Production::findOrFail($id);
        $production->delete();
        return response()->json(null, 204);
    }
}

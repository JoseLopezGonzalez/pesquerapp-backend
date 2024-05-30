<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\SpeciesResource;
use App\Models\Species;
use Illuminate\Http\Request;

class SpeciesController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $species = Species::with('fishingGear')->get();
        return SpeciesResource::collection($species);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'scientific_name' => 'required|string|max:255',
            'fao' => 'required|string|max:3',
            'image' => 'required|string|max:255',
            'fishing_gear_id' => 'required|exists:fishing_gears,id',
        ]);

        $species = Species::create($validated);

        return new SpeciesResource($species);
    }

    /**
     * Display the specified resource.
     */
    public function show(Species $species)
    {
        $species->load('fishingGear');
        return new SpeciesResource($species);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Species $species)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'scientific_name' => 'sometimes|required|string|max:255',
            'fao' => 'sometimes|required|string|max:3',
            'image' => 'sometimes|required|string|max:255',
            'fishing_gear_id' => 'sometimes|required|exists:fishing_gears,id',
        ]);

        $species->update($validated);

        return new SpeciesResource($species);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Species $species)
    {
        $species->delete();

        return response()->noContent();
    }
}

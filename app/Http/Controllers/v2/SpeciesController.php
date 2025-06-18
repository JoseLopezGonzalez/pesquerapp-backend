<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\SpeciesResource;
use App\Http\Resources\v2\SpeciesResource as V2SpeciesResource;
use App\Models\Species;
use Illuminate\Http\Request;

class SpeciesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Species::query();

        /* id */
        if (request()->has('id')) {
            $query->where('id', request()->id);
        }

        /* ids */
        if (request()->has('ids')) {
            $query->whereIn('id', request()->ids);
        }

        /* name like */
        if (request()->has('name')) {
            $query->where('name', 'like', '%' . request()->name . '%');
        }


        /* fishingGears where ir */
        if (request()->has('fishingGears')) {
            $query->whereIn('fishing_gear_id', request()->fishingGears);
        }

        /* fao like */
        if (request()->has('fao')) {
            $query->where('fao', 'like', '%' . request()->fao . '%');
        }

        /* scientific name like */
        if (request()->has('scientificName')) {
            $query->where('scientific_name', 'like', '%' . request()->scientificName . '%');
        }

        /* order by name */
        $query->orderBy('name', 'asc');

        $perPage = request()->input('perPage', 10); // Default a 10 si no se proporciona
        return V2SpeciesResource::collection($query->paginate($perPage));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2',
            'scientificName' => 'required|string|min:2',
            'fao' => ['required', 'regex:/^[A-Z]{3,5}$/'],
            'fishingGearId' => 'required|exists:fishing_gears,id',
        ]);

        $species = Species::create([
            'name' => $validated['name'],
            'scientific_name' => $validated['scientificName'],
            'fao' => $validated['fao'],
            'fishing_gear_id' => $validated['fishingGearId'],
        ]);

        return new V2SpeciesResource($species);
    }

    /**
     * Display the specified resource.
     */
    public function show(Species $species)
    {
        return new V2SpeciesResource($species);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Species $species)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2',
            'scientificName' => 'required|string|min:2',
            'fao' => ['required', 'regex:/^[A-Z]{3,5}$/'],
            'fishingGearId' => 'required|exists:fishing_gears,id',
        ]);

        $species->update([
            'name' => $validated['name'],
            'scientific_name' => $validated['scientificName'],
            'fao' => $validated['fao'],
            'fishing_gear_id' => $validated['fishingGearId'],
        ]);

        return new V2SpeciesResource($species);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Species $species)
    {
        $species->delete();
        return response()->json(['message' => 'Especie eliminada con éxito.']);
    }

    public function destroyMultiple(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:species,id',
        ]);

        Species::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Especies eliminadas con éxito.']);
    }
    /**
     * Get all options for the species select box.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        $species = Species::select('id', 'name') // Selecciona solo los campos necesarios
            ->orderBy('name', 'asc') // Ordena por nombre, opcional
            ->get();

        return response()->json($species);
    }
}

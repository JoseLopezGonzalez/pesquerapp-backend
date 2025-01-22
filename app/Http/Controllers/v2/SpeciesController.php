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

        /* names */
        if (request()->has('names')) {
            $query->whereIn('name', request()->names);
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
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Species $species)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Species $species)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Species $species)
    {
        
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

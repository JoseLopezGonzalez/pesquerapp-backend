<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\TransportResource;
use App\Http\Resources\v2\FishingGearResource;
use App\Http\Resources\v2\TransportResource as V2TransportResource;
use App\Models\FishingGear;
use App\Models\Transport;
use Illuminate\Http\Request;

class FishingGearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = FishingGear::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('ids')) {
            $query->whereIn('id', $request->ids);
        }

        /* Name like */
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }


        /* Order by name*/
        $query->orderBy('name', 'asc');

        $perPage = $request->input('perPage', 12); // Default a 10 si no se proporciona
        return FishingGearResource::collection($query->paginate($perPage));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2',
        ]);

        $fishingGear = FishingGear::create([
            'name' => $validated['name'],
        ]);

        return new FishingGearResource($fishingGear);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
        $fishingGear = FishingGear::findOrFail($id);
        $fishingGear->delete();

        return response()->json(['message' => 'Arte de pesca eliminado con éxito.']);
    }

    public function destroyMultiple(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:fishing_gears,id',
        ]);

        FishingGear::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Artes de pesca eliminadas con éxito.']);
    }

    /**
     * Get all options for the transports select box.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        $fishingGear = FishingGear::select('id', 'name') // Selecciona solo los campos necesarios
            ->orderBy('name', 'asc') // Ordena por nombre, opcional
            ->get();

        return response()->json($fishingGear);
    }
}

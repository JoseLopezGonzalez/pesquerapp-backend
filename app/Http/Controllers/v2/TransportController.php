<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\TransportResource;
use App\Http\Resources\v2\TransportResource as V2TransportResource;
use App\Models\Transport;
use Illuminate\Http\Request;

class TransportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = Transport::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('ids')) {
            $query->whereIn('id', $request->ids);
        }

        if ($request->has('names')) {
            $query->whereIn('name', $request->names);
        }

        /* adrdess like*/
        if ($request->has('address')) {
            $query->where('address', 'like', '%' . $request->address . '%');
        }

        /* Order by name*/
        $query->orderBy('name', 'asc');

        $perPage = $request->input('perPage', 12); // Default a 10 si no se proporciona
        return V2TransportResource::collection($query->paginate($perPage));

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
        //
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
        //
    }

    /**
     * Get all options for the transports select box.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        $transports = Transport::select('id', 'name') // Selecciona solo los campos necesarios
                       ->orderBy('name', 'asc') // Ordena por nombre, opcional
                       ->get();

        return response()->json($transports);
    }
}

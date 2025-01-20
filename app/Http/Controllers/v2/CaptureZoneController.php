<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\TransportResource;
use App\Http\Resources\v2\TransportResource as V2TransportResource;
use App\Models\CaptureZone;
use App\Models\Transport;
use Illuminate\Http\Request;

class CaptureZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

    

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
     * Get all options for the captureZones select box.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        $captureZones = CaptureZone::select('id', 'name') // Selecciona solo los campos necesarios
                       ->orderBy('name', 'asc') // Ordena por nombre, opcional
                       ->get();

        return response()->json($captureZones);
    }
}

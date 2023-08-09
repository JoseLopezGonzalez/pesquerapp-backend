<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Almacen;
use App\Http\Resources\v1\StoreResource;

class AlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return StoreResource::collection(Almacen::all());
        //$almacenes = Almacen::all();
        //return response()->json($almacenes->toArrayAssoc());

        /* $almacenesArray = $almacenes->map(function ($almacen) {
            return $almacen->toArrayAssoc();
        });

        return response()->json($almacenesArray); */
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
        //return Almacen::find($id)->toArrayAssoc();
        return new StoreResource(Almacen::find($id));
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

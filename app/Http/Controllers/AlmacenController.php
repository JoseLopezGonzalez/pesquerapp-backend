<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Almacen;

class AlmacenController extends Controller
{
    public function index()
    {
        $almacenes = Almacen::all();
        return response()->json($almacenes);
    }

    public function store(Request $request)
    {
        $almacen = Almacen::create($request->all());
        return response()->json($almacen, 201);
    }

    public function show($id)
    {
        $almacen = Almacen::findOrFail($id);
        return response()->json($almacen);
    }

    public function update(Request $request, $id)
    {
        $almacen = Almacen::findOrFail($id);
        $almacen->update($request->all());
        return response()->json($almacen, 200);
    }

    public function destroy($id)
    {
        Almacen::destroy($id);
        return response()->json(null, 204);
    }
}

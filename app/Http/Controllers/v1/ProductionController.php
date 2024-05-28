<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ProductionResource;
use App\Models\Production;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    // Obtener todas las producciones
    public function index()
    {
        $productions = Production::all();
        return ProductionResource::collection($productions); // Devuelve todas las producciones formateadas con el resource
    }

    // Crear una nueva producción
    public function store(Request $request)
    {
        // Validar los datos de entrada
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        // Crear una nueva producción
        $production = Production::create($validated);
        return new ProductionResource($production); // Devuelve la producción creada formateada con el resource
    }

    // Obtener una producción específica
    public function show($id)
    {
        $production = Production::findOrFail($id);
        return new ProductionResource($production); // Devuelve la producción especificada formateada con el resource
    }

    // Actualizar una producción específica
    public function update(Request $request, $id)
    {
        // Validar los datos de entrada
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255'
        ]);

        // Actualizar la producción
        $production = Production::findOrFail($id);
        $production->update($validated);
        return new ProductionResource($production); // Devuelve la producción actualizada formateada con el resource
    }

    // Eliminar una producción específica
    public function destroy($id)
    {
        $production = Production::findOrFail($id);
        $production->delete();
        /* return response(null, Response::HTTP_NO_CONTENT); */ // Devuelve una respuesta vacía con un código de estado 204 (Sin Contenido)
    }
}

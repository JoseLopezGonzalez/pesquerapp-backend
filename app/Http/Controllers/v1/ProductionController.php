<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ProductionResource;
use App\Models\Production;
use Illuminate\Http\Request;

class ProductionController extends Controller
{

    public function index(Request $request)
    {
        $query = Production::query();

        // Relaciones adicionales, si son necesarias (e.g., especie o zona de captura)
        $query->with(['species', 'captureZone']);

        // Filtro por ID de producción
        if ($request->has('id')) {
            $id = $request->input('id');
            $query->where('id', 'like', "%{$id}%");
        }

        // Filtro por Lote (acepta múltiples lotes en un array)
        if ($request->has('lots')) {
            $lots = $request->input('lots'); // Espera un array de lotes
            $query->where(function ($q) use ($lots) {
                foreach ($lots as $lot) {
                    $q->orWhere('lot', 'like', "%{$lot}%");
                }
            });
        }


        // Filtro por Fecha (rango de fechas)
        if ($request->has('dates')) {
            $dates = $request->input('dates');

            if (isset($dates['start'])) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dates['start']));
                $query->where('date', '>=', $startDate);
            }

            if (isset($dates['end'])) {
                $endDate = date('Y-m-d 23:59:59', strtotime($dates['end']));
                $query->where('date', '<=', $endDate);
            }
        }

        // Filtro por Notas
        if ($request->has('notes')) {
            $notes = $request->input('notes');
            $query->where('notes', 'like', "%{$notes}%");
        }

        // Filtro por Especie (acepta múltiples IDs en un array)
        if ($request->has('species')) {
            $speciesIds = $request->input('species'); // Espera un array de IDs
            $query->whereIn('species_id', $speciesIds);
        }

        // Filtro por Zona de Captura (acepta múltiples IDs en un array)
        if ($request->has('captureZones')) {
            $captureZoneIds = $request->input('captureZones'); // Espera un array de IDs
            $query->whereIn('capture_zone_id', $captureZoneIds);
        }

        // Ordenar por fecha descendente
        $query->orderBy('date', 'desc');

        // Paginación con un valor predeterminado de 10
        $perPage = $request->input('perPage', 10);
        return ProductionResource::collection($query->paginate($perPage));
    }

    // Método para almacenar la producción inicial sin el diagrama
    public function store(Request $request)
    {
        // Validación de los datos iniciales
        $request->validate([
            'lot' => 'nullable|string',
            'date' => 'nullable|date',
            'species_id' => 'required|exists:species,id',
            'capture_zone_id' => 'required|exists:capture_zones,id',
            'notes' => 'nullable|string',
        ]);

        // Crear un nuevo registro en la tabla productions sin el diagrama
        $production = Production::create([
            'lot' => $request->lot,
            'date' => $request->date,
            'species_id' => $request->species_id,
            'capture_zone_id' => $request->capture_zone_id,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'message' => 'Producción creada correctamente.',
            'production_id' => $production->id
        ]);
    }

    // Método para actualizar una producción existente, incluyendo el diagrama y otros campos
    public function update(Request $request, $id)
    {
        // Validación de todos los campos opcionales
        $request->validate([
            'lot' => 'nullable|string',
            'date' => 'nullable|date',
            'species_id' => 'nullable|exists:species,id',
            'capture_zone_id' => 'nullable|exists:capture_zones,id',
            'notes' => 'nullable|string',
            'diagram_data' => 'nullable|json',
        ]);

        // Buscar la producción y actualizar los campos recibidos
        $production = Production::findOrFail($id);
        $production->update($request->only([
            'lot',
            'date',
            'species_id',
            'capture_zone_id',
            'notes',
            'diagram_data',
        ]));

        return response()->json([
            'message' => 'Producción actualizada correctamente.',
        ]);
    }

    // Mostrar una producción específica
    public function show($id)
    {
        $production = Production::findOrFail($id);
        return new ProductionResource($production);
    }

    // Eliminar una producción específica
    public function destroy($id)
    {
        $production = Production::findOrFail($id);
        $production->delete();

        return response()->json(['message' => 'Producción eliminada correctamente.']);
    }

    public function getProductionIdByLot(Request $request)
    {
        $request->validate([
            'lot' => 'required|string',
        ]);

        // Buscar la producción por el lote
        $production = Production::where('lot', $request->lot)->first();

        if ($production) {
            return response()->json(['production_id' => $production->id]);
        } else {
            return response()->json(['message' => 'Producción no encontrada.'], 404);
        }
        
    }
}

<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinalNodeController extends Controller
{
    public function getFinalNodesProfit(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $speciesId = $request->input('species_id'); // Filtro por especie

    // Validar los parámetros
    if (!$startDate || !$endDate) {
        return response()->json(['error' => 'Las fechas son requeridas'], 400);
    }
    if (!$speciesId) {
        return response()->json(['error' => 'El ID de la especie es requerido'], 400);
    }

    // Filtrar producciones por rango de fechas y especie
    $productions = Production::whereBetween('date', [$startDate, $endDate])
        ->where('species_id', $speciesId)
        ->get();

    // Variables para la agrupación
    $finalData = [];

    foreach ($productions as $production) {
        // Obtener nodos `final` de cada producción
        $finalNodes = $production->getFinalNodes();
        foreach ($finalNodes as $node) {
            $processName = $node['process_name'];

            if (!isset($finalData[$processName])) {
                $finalData[$processName] = [
                    'process_name' => $processName,
                    'total_sold_quantity' => 0,
                    'weighted_profit_sum' => 0,
                ];
            }

            // Agregar datos al grupo
            $finalData[$processName]['total_sold_quantity'] += $node['sold_quantity'];
            $finalData[$processName]['weighted_profit_sum'] += $node['sold_quantity'] * $node['profit_per_kg'];
        }
    }

    // Calcular el beneficio medio ponderado por proceso
    $groupedData = [];
    foreach ($finalData as $process) {
        $totalSold = $process['total_sold_quantity'];
        $groupedData[] = [
            'process_name' => $process['process_name'],
            'average_profit_per_kg' => $totalSold > 0 ? $process['weighted_profit_sum'] / $totalSold : 0,
            'total_sold_quantity' => $totalSold,
        ];
    }

    return response()->json($groupedData);
}

}

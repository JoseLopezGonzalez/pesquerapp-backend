<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Production;
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
                        'total_quantity' => 0,
                        'weighted_profit_sum' => 0,
                        'weighted_cost_sum' => 0,
                        'products' => [], // Detalles de productos
                    ];
                }
    
                // Agregar datos al grupo
                $finalData[$processName]['total_quantity'] += $node['total_quantity'];
                $finalData[$processName]['weighted_profit_sum'] += $node['total_quantity'] * $node['profit_per_kg'];
                $finalData[$processName]['weighted_cost_sum'] += $node['total_quantity'] * $node['cost_per_kg'];
    
                // Agregar detalles de productos
                $finalData[$processName]['products'] = array_merge(
                    $finalData[$processName]['products'],
                    $node['products']
                );
            }
        }
    
        // Calcular el beneficio y el coste medios ponderados por proceso
        $groupedData = [];
        foreach ($finalData as $process) {
            $totalQuantity = $process['total_quantity'];
            $groupedData[] = [
                'process_name' => $process['process_name'],
                'average_profit_per_kg' => $totalQuantity > 0 ? $process['weighted_profit_sum'] / $totalQuantity : 0,
                'average_cost_per_kg' => $totalQuantity > 0 ? $process['weighted_cost_sum'] / $totalQuantity : 0,
                'total_quantity' => $totalQuantity,
                'products' => $process['products'],
            ];
        }
    
        return response()->json($groupedData);
    }
    
    

    

}

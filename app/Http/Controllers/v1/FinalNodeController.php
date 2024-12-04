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
        $speciesId = $request->input('species_id');

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

        // Variables de acumulación global
        $globalTotals = [
            'total_input_quantity' => 0,
            'total_output_quantity' => 0,
            'total_profit' => 0,
            'total_cost_output' => 0,
            'total_profit_output' => 0,
            'total_profit_input' => 0,
        ];

        // Variables para la agrupación por procesos
        $finalData = [];

        foreach ($productions as $production) {
            // Obtener nodos finales de cada producción
            $finalNodes = $production->getFinalNodes();

            foreach ($finalNodes as $node) {
                $processName = $node['process_name'];

                // Inicializar datos del proceso si no existe
                if (!isset($finalData[$processName])) {
                    $finalData[$processName] = [
                        'process_name' => $processName,
                        'total_input_quantity' => 0,
                        'total_output_quantity' => 0,
                        'total_profit_sum' => 0,
                        'weighted_cost_output_sum' => 0,
                        'weighted_profit_output_sum' => 0,
                        'weighted_profit_input_sum' => 0,
                        'weighted_cost_input_sum' => 0, // Nuevo acumulador para input cost
                        'products' => [],
                    ];
                }

                // Datos del nodo
                $totalInputQuantity = $node['total_input_quantity'] ?? 0;
                $totalOutputQuantity = $node['total_output_quantity'] ?? 0;
                $totalProfit = $node['total_profit'] ?? 0;
                $costPerOutputKg = $node['cost_per_output_kg'] ?? 0;
                $profitPerOutputKg = $node['profit_per_output_kg'] ?? 0;
                $profitPerInputKg = $node['profit_per_input_kg'] ?? 0;

                // Calcular coste por kg de entrada (si es necesario en nodos padres)
                $costPerInputKg = $totalInputQuantity > 0 ? $totalProfit / $totalInputQuantity : 0;

                // Actualizar acumuladores globales
                $globalTotals['total_input_quantity'] += $totalInputQuantity;
                $globalTotals['total_output_quantity'] += $totalOutputQuantity;
                $globalTotals['total_profit'] += $totalProfit;
                $globalTotals['total_cost_output'] += $totalOutputQuantity * $costPerOutputKg;
                $globalTotals['total_profit_output'] += $totalOutputQuantity * $profitPerOutputKg;
                $globalTotals['total_profit_input'] += $totalInputQuantity * $profitPerInputKg;

                // Actualizar datos del proceso
                $finalData[$processName]['total_input_quantity'] += $totalInputQuantity;
                $finalData[$processName]['total_output_quantity'] += $totalOutputQuantity;
                $finalData[$processName]['total_profit_sum'] += $totalProfit;
                $finalData[$processName]['weighted_cost_output_sum'] += $totalOutputQuantity * $costPerOutputKg;
                $finalData[$processName]['weighted_cost_input_sum'] += $totalInputQuantity * $costPerInputKg;
                $finalData[$processName]['weighted_profit_output_sum'] += $totalOutputQuantity * $profitPerOutputKg;
                $finalData[$processName]['weighted_profit_input_sum'] += $totalInputQuantity * $profitPerInputKg;
            }
        }

        // Calcular medias globales
        $globalTotals['average_cost_per_output_kg'] = $globalTotals['total_output_quantity'] > 0
            ? $globalTotals['total_cost_output'] / $globalTotals['total_output_quantity']
            : 0;

        $globalTotals['average_profit_per_output_kg'] = $globalTotals['total_output_quantity'] > 0
            ? $globalTotals['total_profit_output'] / $globalTotals['total_output_quantity']
            : 0;

        $globalTotals['average_profit_per_input_kg'] = $globalTotals['total_input_quantity'] > 0
            ? $globalTotals['total_profit_input'] / $globalTotals['total_input_quantity']
            : 0;

        $globalTotals['average_cost_per_input_kg'] = $globalTotals['total_input_quantity'] > 0
            ? $globalTotals['total_profit'] / $globalTotals['total_input_quantity']
            : 0;

        // Procesos detallados
        $processesData = [];
        foreach ($finalData as $processName => $process) {
            $totalInputQuantity = $process['total_input_quantity'];
            $totalOutputQuantity = $process['total_output_quantity'];
            $averageCostPerOutputKg = $totalOutputQuantity > 0
                ? $process['weighted_cost_output_sum'] / $totalOutputQuantity
                : 0;

            $averageCostPerInputKg = $totalInputQuantity > 0
                ? $process['weighted_cost_input_sum'] / $totalInputQuantity
                : 0;

            $averageProfitPerOutputKg = $totalOutputQuantity > 0
                ? $process['weighted_profit_output_sum'] / $totalOutputQuantity
                : 0;

            $averageProfitPerInputKg = $totalInputQuantity > 0
                ? $process['weighted_profit_input_sum'] / $totalInputQuantity
                : 0;

            $processesData[] = [
                'process_name' => $process['process_name'],
                'total_input_quantity' => $totalInputQuantity,
                'total_output_quantity' => $totalOutputQuantity,
                'average_cost_per_output_kg' => $averageCostPerOutputKg,
                'average_cost_per_input_kg' => $averageCostPerInputKg,
                'average_profit_per_output_kg' => $averageProfitPerOutputKg,
                'average_profit_per_input_kg' => $averageProfitPerInputKg,
                'total_profit' => $process['total_profit_sum'],
            ];
        }

        return response()->json([
            'totals' => $globalTotals,
            'processes' => $processesData,
        ]);
    }
}

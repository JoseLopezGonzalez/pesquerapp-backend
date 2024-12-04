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
                $finalData[$processName]['weighted_profit_output_sum'] += $totalOutputQuantity * $profitPerOutputKg;
                $finalData[$processName]['weighted_profit_input_sum'] += $totalInputQuantity * $profitPerInputKg;

                // Procesar productos
                foreach ($node['products'] as $product) {
                    $productName = $product['product_name'];

                    if (!isset($finalData[$processName]['products'][$productName])) {
                        $finalData[$processName]['products'][$productName] = [
                            'product_name' => $productName,
                            'total_quantity' => 0,
                            'weighted_cost_sum' => 0,
                            'weighted_profit_output_sum' => 0,
                            'weighted_profit_input_sum' => 0,
                        ];
                    }

                    $productQuantity = $product['quantity'] ?? 0;
                    $productCostPerOutputKg = $product['cost_per_output_kg'] ?? 0;
                    $productProfitPerOutputKg = $product['profit_per_output_kg'] ?? 0;
                    $productProfitPerInputKg = $product['profit_per_input_kg'] ?? 0;

                    $finalData[$processName]['products'][$productName]['total_quantity'] += $productQuantity;
                    $finalData[$processName]['products'][$productName]['weighted_cost_sum'] += $productQuantity * $productCostPerOutputKg;
                    $finalData[$processName]['products'][$productName]['weighted_profit_output_sum'] += $productQuantity * $productProfitPerOutputKg;
                    $finalData[$processName]['products'][$productName]['weighted_profit_input_sum'] += $productQuantity * $productProfitPerInputKg;
                }
            }
        }

        // Calcular medias ponderadas y estructurar la respuesta
        $processesData = [];
        foreach ($finalData as $processName => $process) {
            $products = [];
            foreach ($process['products'] as $productName => $product) {
                $totalQuantity = $product['total_quantity'];
                $averageCostPerOutputKg = $totalQuantity > 0 ? $product['weighted_cost_sum'] / $totalQuantity : 0;
                $averageProfitPerOutputKg = $totalQuantity > 0 ? $product['weighted_profit_output_sum'] / $totalQuantity : 0;
                $averageProfitPerInputKg = $totalQuantity > 0 ? $product['weighted_profit_input_sum'] / $totalQuantity : 0;
                $margin = $averageCostPerOutputKg > 0 ? ($averageProfitPerOutputKg / $averageCostPerOutputKg) * 100 : 0;

                $products[] = [
                    'product_name' => $product['product_name'],
                    'total_quantity' => $totalQuantity,
                    'average_cost_per_output_kg' => $averageCostPerOutputKg,
                    'average_profit_per_output_kg' => $averageProfitPerOutputKg,
                    'average_profit_per_input_kg' => $averageProfitPerInputKg,
                    'margin' => $margin,
                ];
            }

            $totalInputQuantity = $process['total_input_quantity'];
            $totalOutputQuantity = $process['total_output_quantity'];
            $averageCostPerOutputKg = $totalOutputQuantity > 0 ? $process['weighted_cost_output_sum'] / $totalOutputQuantity : 0;
            $averageProfitPerOutputKg = $totalOutputQuantity > 0 ? $process['weighted_profit_output_sum'] / $totalOutputQuantity : 0;
            $averageProfitPerInputKg = $totalInputQuantity > 0 ? $process['weighted_profit_input_sum'] / $totalInputQuantity : 0;

            $processesData[] = [
                'process_name' => $process['process_name'],
                'total_input_quantity' => $totalInputQuantity,
                'total_output_quantity' => $totalOutputQuantity,
                'average_cost_per_output_kg' => $averageCostPerOutputKg,
                'average_profit_per_output_kg' => $averageProfitPerOutputKg,
                'average_profit_per_input_kg' => $averageProfitPerInputKg,
                'total_profit' => $process['total_profit_sum'],
                'products' => $products,
            ];
        }

        // Calcular medias globales
        $globalTotals['average_cost_per_output_kg'] = $globalTotals['total_output_quantity'] > 0
            ? $globalTotals['total_cost_output'] / $globalTotals['total_output_quantity']
            : 0;

        return response()->json([
            'totals' => $globalTotals,
            'processes' => $processesData,
        ]);
    }
}

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
            'total_quantity' => 0,
            'total_profit' => 0,
            'total_cost' => 0,
        ];

        // Variables para la agrupación por procesos
        $finalData = [];

        foreach ($productions as $production) {
            // Obtener nodos `final` de cada producción
            $finalNodes = $production->getFinalNodes();
            foreach ($finalNodes as $node) {
                $processName = $node['process_name'];

                // Inicializar datos del proceso si no existe
                if (!isset($finalData[$processName])) {
                    $finalData[$processName] = [
                        'process_name' => $processName,
                        'total_quantity' => 0,
                        'weighted_profit_sum' => 0,
                        'weighted_cost_sum' => 0,
                        'products' => [],
                    ];
                }

                $totalQuantity = $node['total_quantity'] ?? 0;
                $profitPerKg = $node['profit_per_kg'] ?? 0;
                $costPerKg = $node['cost_per_kg'] ?? 0;

                // Actualizar datos globales
                $globalTotals['total_quantity'] += $totalQuantity;
                $globalTotals['total_profit'] += $totalQuantity * $profitPerKg;
                $globalTotals['total_cost'] += $totalQuantity * $costPerKg;

                // Actualizar datos del proceso
                $finalData[$processName]['total_quantity'] += $totalQuantity;
                $finalData[$processName]['weighted_profit_sum'] += $totalQuantity * $profitPerKg;
                $finalData[$processName]['weighted_cost_sum'] += $totalQuantity * $costPerKg;

                // Procesar productos
                foreach ($node['products'] as $product) {
                    $productName = $product['product_name'];

                    if (!isset($finalData[$processName]['products'][$productName])) {
                        $finalData[$processName]['products'][$productName] = [
                            'product_name' => $productName,
                            'total_quantity' => 0,
                            'weighted_cost_sum' => 0,
                            'weighted_profit_sum' => 0,
                        ];
                    }

                    $productQuantity = $product['quantity'] ?? 0;
                    $productCostPerKg = $product['cost_per_kg'] ?? 0;
                    $productProfitPerKg = $product['profit_per_kg'] ?? 0;

                    $finalData[$processName]['products'][$productName]['total_quantity'] += $productQuantity;
                    $finalData[$processName]['products'][$productName]['weighted_cost_sum'] += $productQuantity * $productCostPerKg;
                    $finalData[$processName]['products'][$productName]['weighted_profit_sum'] += $productQuantity * $productProfitPerKg;
                }
            }
        }

        // Calcular medias ponderadas y estructurar la respuesta
        $processesData = [];
        foreach ($finalData as $processName => $process) {
            $products = [];
            foreach ($process['products'] as $productName => $product) {
                $totalQuantity = $product['total_quantity'];
                $averageCostPerKg = $totalQuantity > 0 ? $product['weighted_cost_sum'] / $totalQuantity : 0;
                $averageProfitPerKg = $totalQuantity > 0 ? $product['weighted_profit_sum'] / $totalQuantity : 0;
                $margin = $averageCostPerKg > 0 ? ($averageProfitPerKg / $averageCostPerKg) * 100 : 0;

                $products[] = [
                    'product_name' => $product['product_name'],
                    'total_quantity' => $totalQuantity,
                    'average_cost_per_kg' => $averageCostPerKg,
                    'average_profit_per_kg' => $averageProfitPerKg,
                    'margin' => $margin,
                ];
            }

            $totalQuantity = $process['total_quantity'];
            $averageCostPerKg = $totalQuantity > 0 ? $process['weighted_cost_sum'] / $totalQuantity : 0;
            $averageProfitPerKg = $totalQuantity > 0 ? $process['weighted_profit_sum'] / $totalQuantity : 0;
            $margin = $averageCostPerKg > 0 ? ($averageProfitPerKg / $averageCostPerKg) * 100 : 0;

            $processesData[] = [
                'process_name' => $process['process_name'],
                'average_profit_per_kg' => $averageProfitPerKg,
                'average_cost_per_kg' => $averageCostPerKg,
                'margin' => $margin,
                'total_quantity' => $totalQuantity,
                'products' => $products,
            ];
        }

        // Calcular medias globales
        $globalTotals['average_profit_per_kg'] = $globalTotals['total_quantity'] > 0
            ? $globalTotals['total_profit'] / $globalTotals['total_quantity']
            : 0;
        $globalTotals['average_cost_per_kg'] = $globalTotals['total_quantity'] > 0
            ? $globalTotals['total_cost'] / $globalTotals['total_quantity']
            : 0;
        $globalTotals['margin'] = $globalTotals['average_cost_per_kg'] > 0
            ? ($globalTotals['average_profit_per_kg'] / $globalTotals['average_cost_per_kg']) * 100
            : 0;

        return response()->json([
            'totals' => $globalTotals,
            'processes' => $processesData,
        ]);
    }
    

    
    

    

}

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
            'total_cost' => 0,
            'total_profit' => 0,
            'average_cost_per_output_kg' => 0,
            'average_cost_per_input_kg' => 0,
            'average_profit_per_output_kg' => 0,
            'average_profit_per_input_kg' => 0,
            'margin' => 0,
        ];

        // Variables para la agrupación por procesos
        $finalData = [];

        foreach ($productions as $production) {
            $finalNodes = $production->getFinalNodes();

            foreach ($finalNodes as $node) {
                $processName = $node['process_name'];

                if (!isset($finalData[$processName])) {
                    $finalData[$processName] = [
                        'process_name' => $processName,
                        'total_input_quantity' => 0,
                        'total_output_quantity' => 0,
                        'total_profit_sum' => 0,
                        'weighted_cost_sum' => 0,
                        'weighted_profit_output_sum' => 0,
                        'weighted_profit_input_sum' => 0,
                        'products' => [],
                    ];
                }

                $totalInputQuantity = $node['total_input_quantity'] ?? 0;
                $totalOutputQuantity = $node['total_output_quantity'] ?? 0;
                $totalProfit = $node['total_profit'] ?? 0;
                $costPerOutputKg = $node['cost_per_output_kg'] ?? 0;

                // Calcular el coste por kg de entrada
                $costPerInputKg = $totalInputQuantity > 0
                    ? $finalData[$processName]['weighted_cost_sum'] / $totalInputQuantity
                    : 0;

                // Actualizar totales globales
                $globalTotals['total_input_quantity'] += $totalInputQuantity;
                $globalTotals['total_output_quantity'] += $totalOutputQuantity;
                $globalTotals['total_cost'] += $totalOutputQuantity * $costPerOutputKg;
                $globalTotals['total_profit'] += $totalProfit;

                // Actualizar datos del nodo
                $finalData[$processName]['total_input_quantity'] += $totalInputQuantity;
                $finalData[$processName]['total_output_quantity'] += $totalOutputQuantity;
                $finalData[$processName]['total_profit_sum'] += $totalProfit;
                $finalData[$processName]['weighted_cost_sum'] += $totalOutputQuantity * $costPerOutputKg;
                $finalData[$processName]['weighted_profit_output_sum'] += $totalOutputQuantity * $node['profit_per_output_kg'];
                $finalData[$processName]['weighted_profit_input_sum'] += $totalInputQuantity * $node['profit_per_input_kg'];

                foreach ($node['products'] as $product) {
                    $productName = $product['product_name'];

                    if (!isset($finalData[$processName]['products'][$productName])) {
                        $finalData[$processName]['products'][$productName] = [
                            'product_name' => $productName,
                            'total_input_quantity' => 0,
                            'total_output_quantity' => 0,
                            'weighted_cost_sum' => 0,
                            'weighted_profit_output_sum' => 0,
                            'weighted_profit_input_sum' => 0,
                        ];
                    }

                    $productInputQuantity = $product['initial_quantity'] ?? 0;
                    $productOutputQuantity = $product['output_quantity'] ?? 0;
                    $productCostPerKg = $product['cost_per_kg'] ?? 0;

                    // Calcular costes por kg de entrada y salida para el producto
                    $costPerInputKg = $productInputQuantity > 0
                        ? $product['weighted_cost_sum'] / $productInputQuantity
                        : 0;

                    $costPerOutputKg = $productOutputQuantity > 0
                        ? $product['weighted_cost_sum'] / $productOutputQuantity
                        : 0;

                    // Actualizar totales del producto
                    $finalData[$processName]['products'][$productName]['total_input_quantity'] += $productInputQuantity;
                    $finalData[$processName]['products'][$productName]['total_output_quantity'] += $productOutputQuantity;
                    $finalData[$processName]['products'][$productName]['weighted_cost_sum'] += $productOutputQuantity * $productCostPerKg;
                    $finalData[$processName]['products'][$productName]['weighted_profit_output_sum'] += $productOutputQuantity * $product['profit_per_output_kg'];
                    $finalData[$processName]['products'][$productName]['weighted_profit_input_sum'] += $productInputQuantity * $product['profit_per_input_kg'];

                    // Agregar los costes por kg de entrada y salida al producto
                    $finalData[$processName]['products'][$productName]['cost_per_input_kg'] = $costPerInputKg;
                    $finalData[$processName]['products'][$productName]['cost_per_output_kg'] = $costPerOutputKg;
                }
            }
        }

        $processesData = [];
        foreach ($finalData as $processName => $process) {
            $products = [];
            foreach ($process['products'] as $productName => $product) {
                $totalInputQuantity = $product['total_input_quantity'];
                $totalOutputQuantity = $product['total_output_quantity'];

                $products[] = [
                    'product_name' => $product['product_name'],
                    'total_input_quantity' => $totalInputQuantity,
                    'total_output_quantity' => $totalOutputQuantity,
                    'cost_per_input_kg' => $product['cost_per_input_kg'],
                    'cost_per_output_kg' => $product['cost_per_output_kg'],
                ];
            }

            $processesData[] = [
                'process_name' => $process['process_name'],
                'total_input_quantity' => $process['total_input_quantity'],
                'total_output_quantity' => $process['total_output_quantity'],
                'cost_per_input_kg' => $costPerInputKg,
                'cost_per_output_kg' => $costPerOutputKg,
                'products' => $products,
            ];
        }

        $globalTotals['average_cost_per_output_kg'] = $globalTotals['total_output_quantity'] > 0
            ? $globalTotals['total_cost'] / $globalTotals['total_output_quantity']
            : 0;

        $globalTotals['average_cost_per_input_kg'] = $globalTotals['total_input_quantity'] > 0
            ? $globalTotals['total_cost'] / $globalTotals['total_input_quantity']
            : 0;

        return response()->json([
            'totals' => $globalTotals,
            'processes' => $processesData,
        ]);
    }
}

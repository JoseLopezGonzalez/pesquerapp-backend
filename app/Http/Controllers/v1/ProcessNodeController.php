<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Production;
use Illuminate\Http\Request;

class ProcessNodeController extends Controller
{
    /**
     * Obtener nodos de tipo 'process' y calcular la merma media ponderada.
     */
    public function getProcessNodesDecrease(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Validar las fechas
        if (!$startDate || !$endDate) {
            return response()->json(['error' => 'Las fechas son requeridas'], 400);
        }

        // Filtrar producciones por rango de fechas
        $productions = Production::whereBetween('date', [$startDate, $endDate])->get();

        // Variables para el cálculo
        $processData = [];
        $totalInputQuantity = 0;
        $weightedDecreaseSum = 0;

        foreach ($productions as $production) {
            // Obtener nodos `process` de cada producción
            $processNodes = $production->getProcessNodes();
            foreach ($processNodes as $node) {
                $processData[] = $node; // Agregar al listado
                $totalInputQuantity += $node['input_quantity'];
                $weightedDecreaseSum += $node['input_quantity'] * $node['decrease'];
            }
        }

        // Calcular la merma media ponderada
        $averageDecrease = $totalInputQuantity > 0 ? $weightedDecreaseSum / $totalInputQuantity : 0;

        return response()->json([
            'process_nodes' => $processData,
            'average_decrease' => $averageDecrease,
        ]);
    }
}

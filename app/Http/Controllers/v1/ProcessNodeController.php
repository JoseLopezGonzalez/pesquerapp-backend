<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Production;
use Illuminate\Http\Request;

class ProcessNodeController extends Controller
{
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

    // Variables para la agrupación
    $processData = [];

    foreach ($productions as $production) {
        // Obtener nodos `process` de cada producción
        $processNodes = $production->getProcessNodes();
        foreach ($processNodes as $node) {
            $processName = $node['process_name'];

            if (!isset($processData[$processName])) {
                $processData[$processName] = [
                    'process_name' => $processName,
                    'total_input_quantity' => 0,
                    'weighted_loss_sum' => 0,
                ];
            }

            // Agregar datos al grupo
            $processData[$processName]['total_input_quantity'] += $node['input_quantity'];
            $processData[$processName]['weighted_loss_sum'] += $node['input_quantity'] * $node['decrease'];
        }
    }

    // Calcular la merma media ponderada por proceso
    $groupedData = [];
    foreach ($processData as $process) {
        $totalInput = $process['total_input_quantity'];
        $groupedData[] = [
            'process_name' => $process['process_name'],
            'average_decrease' => $totalInput > 0 ? $process['weighted_loss_sum'] / $totalInput : 0,
            'total_input_quantity' => $totalInput,
        ];
    }

    return response()->json($groupedData);
}

}

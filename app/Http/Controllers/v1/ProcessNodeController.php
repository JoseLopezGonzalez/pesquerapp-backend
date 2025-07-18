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

    public function getProcessNodesDecreaseStats(Request $request)
    {
        // Obtener parámetros de entrada
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

        // Variable para agrupar por fecha y proceso
        $dateWiseData = [];

        foreach ($productions as $production) {
            $date = $production->date; // Fecha de la producción

            // Inicializar la fecha si no existe en el agrupamiento
            if (!isset($dateWiseData[$date])) {
                $dateWiseData[$date] = [
                    'name' => $date,
                ];
            }

            // Obtener nodos `process` de cada producción
            $processNodes = $production->getProcessNodes();
            foreach ($processNodes as $node) {
                $processName = $node['process_name']; // Nombre del proceso
                $inputQuantity = $node['input_quantity']; // Cantidad de entrada
                $decrease = $node['decrease']; // Merma

                // Inicializar el proceso en la fecha si no existe
                if (!isset($dateWiseData[$date][$processName])) {
                    $dateWiseData[$date][$processName] = [
                        'total_input_quantity' => 0,
                        'weighted_loss_sum' => 0,
                    ];
                }

                // Sumar la cantidad de entrada y calcular la suma ponderada
                $dateWiseData[$date][$processName]['total_input_quantity'] += $inputQuantity;
                $dateWiseData[$date][$processName]['weighted_loss_sum'] += $inputQuantity * $decrease;
            }
        }

        // Ordenar las fechas (claves del array) cronológicamente
        ksort($dateWiseData);

        // Transformar los datos en el formato requerido
        $formattedData = [];

        foreach ($dateWiseData as $date => $processes) {
            $entry = ['name' => $date]; // Iniciar la fila con el nombre (fecha)

            foreach ($processes as $processName => $data) {
                if ($processName !== 'name') {
                    $totalInput = $data['total_input_quantity'];
                    $averageDecrease = $totalInput > 0
                        ? $data['weighted_loss_sum'] / $totalInput // Calcular media ponderada
                        : 0;
                    $entry[$processName] = $averageDecrease; // Agregar la merma media
                }
            }

            $formattedData[] = $entry; // Agregar la fila a la salida final
        }

        // Retornar la respuesta final
        return response()->json($formattedData);
    }
}

<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\RawMaterialReception;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;
use Illuminate\Http\Request;

class RawMaterialReceptionsStatsController extends Controller
{
    /* Devolver net weight del mes completo, comparativa en % de el mes con respecto al anterior, y un array con cada dia del mes con sus respectivos net weight */
    public function getMonthlyStats(Request $request)
    {
        
            // Validar la entrada
            $validator = Validator::make($request->all(), [
                'month' => 'required|date_format:Y-m', // Espera un formato de mes y año 'YYYY-MM'
                'species' => 'required', // Especie requerida
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422); // Código de estado 422 - Unprocessable Entity
            }
    
            $month = Carbon::createFromFormat('Y-m', $request->month);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();

            /* No funcionan */
            /* $previousMonth = $month->copy()->subMonth();
            $startOfPreviousMonth = $previousMonth->startOfMonth();
            $endOfPreviousMonth = $previousMonth->endOfMonth(); */

            $previousMonth = $month->copy()->subMonth();
            $startOfPreviousMonth = $previousMonth->copy()->startOfMonth();
            $endOfPreviousMonth = $previousMonth->copy()->endOfMonth();
            

            $speciesId = $request->species;
    
            /* Obtener totalNetWeight del mes de la ESPECIE pasada por parámetro */
            $totalNetWeightCurrentMonth = RawMaterialReception::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->with(['products' => function ($query) use ($speciesId) {
                    $query->whereHas('product', function ($query) use ($speciesId) {
                        $query->where('species_id', $speciesId);
                    });
                }])
                ->get()
                ->reduce(function ($carry, $reception) {
                    return $carry + $reception->products->sum('net_weight');
                }, 0);
    
            /* Obtener totalNetWeight para el mes anterior según especie  (NO FUNCIONA)*/
            $totalNetWeightPreviousMonth = RawMaterialReception::whereBetween('date', [$startOfPreviousMonth, $endOfPreviousMonth])
                ->with(['products' => function ($query) use ($speciesId) {
                    $query->whereHas('product', function ($query) use ($speciesId) {
                        $query->where('species_id', $speciesId);
                    });
                }])
                ->get()
                ->reduce(function ($carry, $reception) {
                    return $carry + $reception->products->sum('net_weight');
                }, 0);
    
            /* Calcular la comparativa en porcentaje con el mes anterior */
            $percentageChange = $totalNetWeightPreviousMonth > 0
                ? (($totalNetWeightCurrentMonth - $totalNetWeightPreviousMonth) / $totalNetWeightPreviousMonth) * 100
                : null;
    
            /* Obtener los datos de peso neto por día para el mes actual */
            $currentMonthData = RawMaterialReception::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->with(['products' => function ($query) use ($speciesId) {
                    $query->whereHas('product', function ($query) use ($speciesId) {
                        $query->where('species_id', $speciesId);
                    });
                }])
                ->get()
                ->groupBy(function ($date) {
                    return Carbon::parse($date->date)->format('d'); // Agrupar por día del mes
                })
                ->map(function ($day) {
                    return $day->reduce(function ($carry, $reception) {
                        return $carry + $reception->products->sum('net_weight');
                    }, 0);
                });
    

            /* dailyNetWeights debe ser un array de objetos cuando sea json */
            $dailyNetWeights = $currentMonthData->map(function ($weight, $day) use ($startOfMonth) {
                return [
                    'name' => $startOfMonth->copy()->addDays($day - 1)->format('d-m-Y'),
                    'currentMonth' => $weight,
                    'previousMonth' => 0,
                ];
            })->values()->all();

    
            /* Formato data = "" */
            return response()->json([
                'data' => [
                    'totalNetWeight' => $totalNetWeightCurrentMonth,
                    'percentageChange' => $percentageChange,
                    'dailyNetWeights' => $dailyNetWeights,
                    'totalNetWeightPreviousMonth' => $totalNetWeightPreviousMonth
                ]
            ]);
        }
}

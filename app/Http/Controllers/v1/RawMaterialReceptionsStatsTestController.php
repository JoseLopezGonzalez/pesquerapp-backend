<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\RawMaterialReception;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;
use Illuminate\Http\Request;

class RawMaterialReceptionsStatsTestController extends Controller
{
    /* Devolver net weight del mes completo, comparativa en % de el mes con respecto al anterior, y un array con cada dia del mes con sus respectivos net weight */
    public function getMonthlyStats(Request $request)
    {
        // Validar la entrada
        /* $request->validate([
            'month' => 'required|date_format:Y-m', // Espera un formato de mes y año 'YYYY-MM'
        ]); */

        $validator = Validator::make($request->all(), [
            'month' => 'required|date_format:Y-m', // Espera un formato de mes y año 'YYYY-MM'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Código de estado 422 - Unprocessable Entity
        }

        $month = Carbon::createFromFormat('Y-m', $request->month);
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();
        $previousMonth = $month->copy()->subMonth();
        $startOfPreviousMonth = $previousMonth->startOfMonth();
        $endOfPreviousMonth = $previousMonth->endOfMonth();


        /* Obtener totalNetWeight del mes */
        $totalNetWeightCurrentMonth = RawMaterialReception::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->with('products')
            ->get()
            ->reduce(function ($carry, $reception) {
                return $carry + $reception->products->sum('net_weight');
            }, 0);

        /* Obtener totalNerWeight para el mes anterior */
        $totalNetWeightPreviousMonth = RawMaterialReception::whereBetween('date', [$startOfPreviousMonth, $endOfPreviousMonth])
            ->with('products')
            ->get()
            ->reduce(function ($carry, $reception) {
                return $carry + $reception->products->sum('net_weight');
            }, 0);

        /* Calcular la comparativa en porcentaje con el mes anterior */
        $percentageChange = $totalNetWeightPreviousMonth > 0
            ? (($totalNetWeightCurrentMonth - $totalNetWeightPreviousMonth) / $totalNetWeightPreviousMonth) * 100
            : null;

        /* Obtener los datos de peso neto por día 
        con el siguiente formato 
        {
            "name" : "01" , //Dia
            "currentMonth" : 1000, //Peso neto del dia
            "previousMonth" : 500 //Peso neto del dia

        }
        */
        $currentMonthData = RawMaterialReception::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->with('products')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->date)->format('d'); // Agrupar por día del mes
            })
            ->map(function ($day) {
                return $day->reduce(function ($carry, $reception) {
                    return $carry + $reception->products->sum('net_weight');
                }, 0);
            });

        $previousMonthData = RawMaterialReception::whereBetween('date', [$startOfPreviousMonth, $endOfPreviousMonth])
            ->with('products')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->date)->format('d'); // Agrupar por día del mes
            })
            ->map(function ($day) {
                return $day->reduce(function ($carry, $reception) {
                    return $carry + $reception->products->sum('net_weight');
                }, 0);
            });

        // Combina los datos de ambos meses para obtener el formato requerido
        $dailyNetWeights = collect(range(1, $endOfMonth->format('d')))->map(function ($day) use ($currentMonthData, $previousMonthData) {
            $dayFormatted = str_pad($day, 2, '0', STR_PAD_LEFT); // Asegura que los días tengan dos dígitos

            return [
                'name' => $dayFormatted,
                'currentMonth' => $currentMonthData->get($dayFormatted, 0), // Peso neto del día para el mes actual
                'previousMonth' => $previousMonthData->get($dayFormatted, 0) // Peso neto del día para el mes anterior
            ];
        });


/* Formato data = "" */
        return response()->json([
            'data' => [
                'totalNetWeight' => $totalNetWeightCurrentMonth,
                'percentageChange' => $percentageChange,
                'dailyNetWeights' => $dailyNetWeights,
            ]
        ]);
    }
}

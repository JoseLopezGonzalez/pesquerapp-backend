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

    /*     // Obtener el peso neto total para el mes solicitado
        $totalNetWeightCurrentMonth = RawMaterialReception::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->with('products')
            ->get()
            ->reduce(function ($carry, $reception) {
                return $carry + $reception->products->sum('net_weight');
            }, 0);

        // Obtener el peso neto total para el mes anterior
        $totalNetWeightPreviousMonth = RawMaterialReception::whereBetween('date', [$startOfPreviousMonth, $endOfPreviousMonth])
            ->with('products')
            ->get()
            ->reduce(function ($carry, $reception) {
                return $carry + $reception->products->sum('net_weight');
            }, 0);

        // Calcular la comparativa en porcentaje con el mes anterior
        $percentageChange = $totalNetWeightPreviousMonth > 0
            ? (($totalNetWeightCurrentMonth - $totalNetWeightPreviousMonth) / $totalNetWeightPreviousMonth) * 100
            : null;

        // Obtener los datos de peso neto por día
        $dailyNetWeights = RawMaterialReception::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->with('products')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->date)->format('Y-m-d');
            })
            ->map(function ($day) {
                return $day->reduce(function ($carry, $reception) {
                    return $carry + $reception->products->sum('net_weight');
                }, 0);
            });

        // Devolver la respuesta en formato JSON
        return response()->json([
            'totalNetWeight' => $totalNetWeightCurrentMonth,
            'percentageChange' => $percentageChange,
            'dailyNetWeights' => $dailyNetWeights,
        ]); */

        return ['hola' => 'mundo'];
        

    }
        
}

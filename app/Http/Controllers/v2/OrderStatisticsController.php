<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\v2\OrderStatisticsService;
use Illuminate\Http\Request;

class OrderStatisticsController extends Controller
{
    /**
     * Devuelve estadísticas de peso neto de pedidos en un rango de fechas,
     * comparadas con el mismo rango del año anterior.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * Formato de respuesta:
     * {
     *   "value": float,               // Total del peso neto en el rango actual
     *   "comparisonValue": float,    // Total del peso neto en el mismo rango del año anterior
     *   "percentageChange": float|null, // Diferencia porcentual entre ambos periodos
     *   "range": {
     *     "from": string,            // Fecha de inicio del rango actual (YYYY-MM-DD HH:MM:SS)
     *     "to": string,              // Fecha de fin del rango actual
     *     "fromPrev": string,        // Fecha de inicio del rango del año anterior
     *     "toPrev": string           // Fecha de fin del rango del año anterior
     *   }
     * }
     */
    public function totalNetWeightStats(Request $request)
    {
        $validated = $request->validate([
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date',
            'speciesId' => 'nullable|integer|exists:species,id',
        ]);

        $result = OrderStatisticsService::getNetWeightStatsComparedToLastYear(
            $validated['dateFrom'],
            $validated['dateTo'],
            $validated['speciesId'] ?? null
        );

        return response()->json($result);
    }
}

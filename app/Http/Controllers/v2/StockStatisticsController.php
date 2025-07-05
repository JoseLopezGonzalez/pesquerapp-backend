<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\v2\StockStatisticsService;
use Illuminate\Http\Request;

class StockStatisticsController extends Controller
{
    /**
     * Devuelve estadísticas generales del stock almacenado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * Formato de respuesta:
     * {
     *   "totalNetWeight": float,     // Peso neto total (en kg) de todas las cajas en palets almacenados
     *   "totalPallets": int,      // Número total de palets en estado almacenado
     *   "totalBoxes": int,        // Número total de cajas asociadas a palets almacenados
     *   "totalSpecies": int,      // Número de especies distintas presentes en los artículos almacenados
     *   "totalStores": int        // Número total de almacenes distintos que contienen palets almacenados
     * }
     */
    public function totalStockStats()
    {
        $stats = StockStatisticsService::getTotalStockStats();

        return response()->json($stats);
    }

}

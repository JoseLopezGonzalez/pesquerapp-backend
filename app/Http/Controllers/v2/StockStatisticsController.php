<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\v2\StockStatisticsService;
use Illuminate\Http\Request;

class StockStatisticsController extends Controller
{
    
    public function totalStockStats()
    {
        $stats = StockStatisticsService::getTotalStockStats();

        return response()->json($stats);
    }

}

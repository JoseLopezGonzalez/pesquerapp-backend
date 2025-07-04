<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\v2\OrderStatisticsService;
use Illuminate\Http\Request;

class OrderStatisticsController extends Controller
{
    //
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

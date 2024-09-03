<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\RawMaterialReception;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;
use Illuminate\Http\Request;

// Establecer el locale a español
Carbon::setLocale('es');

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

        /* Ordenar los datos diarios por el día (la clave del grupo) */
        $currentMonthData = $currentMonthData->sortKeys();

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

    /* Anual stats  segun el año pasado por parametro*/
    public function getAnnualStats(Request $request)
    {
        // Validar la entrada
        $validator = Validator::make($request->all(), [
            'year' => 'required|date_format:Y', // Espera un formato de año 'YYYY'
            'species' => 'required', // Especie requerida
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Código de estado 422 - Unprocessable Entity
        }

        $year = Carbon::createFromFormat('Y', $request->year);
        $startOfYear = $year->copy()->startOfYear();
        $endOfYear = $year->copy()->endOfYear();

        $previousYear = $year->copy()->subYear();
        $startOfPreviousYear = $previousYear->copy()->startOfYear();
        $endOfPreviousYear = $previousYear->copy()->endOfYear();

        $speciesId = $request->species;

        /* Obtener totalNetWeight del año de la ESPECIE pasada por parámetro */
        $totalNetWeightCurrentYear = RawMaterialReception::whereBetween('date', [$startOfYear, $endOfYear])
            ->with(['products' => function ($query) use ($speciesId) {
                $query->whereHas('product', function ($query) use ($speciesId) {
                    $query->where('species_id', $speciesId);
                });
            }])
            ->get()
            ->reduce(function ($carry, $reception) {
                return $carry + $reception->products->sum('net_weight');
            }, 0);

        /* Obtener totalNetWeight para el año anterior según especie */
        $totalNetWeightPreviousYear = RawMaterialReception::whereBetween('date', [$startOfPreviousYear, $endOfPreviousYear])
            ->with(['products' => function ($query) use ($speciesId) {
                $query->whereHas('product', function ($query) use ($speciesId) {
                    $query->where('species_id', $speciesId);
                });
            }])
            ->get()
            ->reduce(function ($carry, $reception) {
                return $carry + $reception->products->sum('net_weight');
            }, 0);

        /* Calcular la comparativa en porcentaje con el año anterior */

        $percentageChange = $totalNetWeightPreviousYear > 0
            ? (($totalNetWeightCurrentYear - $totalNetWeightPreviousYear) / $totalNetWeightPreviousYear) * 100
            : null;

        /* Obtener los datos de peso neto por mes para el año actual, ordenados según el orden de los meses naturales */
        $currentYearData = RawMaterialReception::whereBetween('date', [$startOfYear, $endOfYear])
            ->with(['products' => function ($query) use ($speciesId) {
                $query->whereHas('product', function ($query) use ($speciesId) {
                    $query->where('species_id', $speciesId);
                });
            }])
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->date)->format('m'); // Agrupar por mes
            })
            ->map(function ($month) {
                return $month->reduce(function ($carry, $reception) {
                    return $carry + $reception->products->sum('net_weight');
                }, 0);
            });

        /* Ordenar los datos mensuales por el mes (la clave del grupo) */
        $currentYearData = $currentYearData->sortKeys();

        /* monthlyNetWeights debe ser un array de objetos cuando sea json, ordenados según el orden de los meses naturales */
        $monthlyNetWeights = $currentYearData->map(function ($weight, $month) {
            return [
                'name' => Carbon::createFromFormat('m', $month)->format('F'),
                'currentYear' => $weight,
                'previousYear' => 0,
            ];
        })->values()->all();

        /* Formato data = "" */
        return response()->json([
            'data' => [
                'totalNetWeight' => $totalNetWeightCurrentYear,
                'percentageChange' => $percentageChange,
                'monthlyNetWeights' => $monthlyNetWeights,
                'totalNetWeightPreviousYear' => $totalNetWeightPreviousYear
            ]
        ]);
    }

    /* Devolver el peso neto total y el peso neto total por producto pasado por parametros el dia y la especie 
    
    
    data => [
        totalNetWeight => 1234.56,
        totalNetWeightByProducts => [
            [
                name => 'Product 1',
                totalNetWeight => 123.45
            ],
            [
                name => 'Product 2',
                totalNetWeight => 234.56
            ],
            ...
        ]
    ]
    */
    public  function getDailyByProductsStats(Request $request)
    {
        // Validar la entrada
        $validator = Validator::make($request->all(), [
            'date' => 'required|date', // Espera un formato de fecha 'YYYY-MM-DD'
            'species' => 'required', // Especie requerida
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Código de estado 422 - Unprocessable Entity
        }

        $date = Carbon::createFromFormat('Y-m-d', $request->date);
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        $speciesId = $request->species;

        /* Obtener totalNetWeight del día de la ESPECIE pasada por parámetro */
        $totalNetWeight = RawMaterialReception::whereBetween('date', [$startOfDay, $endOfDay])
            ->with(['products' => function ($query) use ($speciesId) {
                $query->whereHas('product', function ($query) use ($speciesId) {
                    $query->where('species_id', $speciesId);
                });
            }])
            ->get()
            ->reduce(function ($carry, $reception) {
                return $carry + $reception->products->sum('net_weight');
            }, 0);

        /* Obtener el peso neto total por producto para el día actual */
        $totalNetWeightByProducts = RawMaterialReception::whereBetween('date', [$startOfDay, $endOfDay])
            ->with(['products' => function ($query) use ($speciesId) {
                $query->whereHas('product', function ($query) use ($speciesId) {
                    $query->where('species_id', $speciesId);
                });
            }])
            ->get()
            ->flatMap(function ($reception) {
                return $reception->products->map(function ($product) {
                    return [
                        'name' => $product->product->article->name,
                        'totalNetWeight' => $product->net_weight,
                    ];
                });
            })
            ->groupBy('name')
            ->map(function ($products , $totalNetWeight) {
                return [
                    'name' => $products->first()['name'],
                    'totalNetWeight' => $products->sum('totalNetWeight'),
                    /* 'percentage' => $products->sum('totalNetWeight') / $totalNetWeight * 100 /* O cero si el divisor es 0 */  */
                ];
            })
            ->values()
            ->all();

            /* Añadir el campo Porcentaje para que quede tal que asi
                    'name' => '...'
                    'totalNetWeight' => '...',
                    'percentage' => '...'
            
            
            */

            if($totalNetWeight > 0){
                $totalNetWeightByProducts = collect($totalNetWeightByProducts)->map(function ($product) use ($totalNetWeight) {
                    return array_merge($product, [
                        'percentage' => $product['totalNetWeight'] / $totalNetWeight * 100
                    ]);
                })->values()->all();
            }else{
                $totalNetWeightByProducts = collect($totalNetWeightByProducts)->map(function ($product) {
                    return array_merge($product, [
                        'percentage' => 0
                    ]);
                })->values()->all();
            }

            



        /* Formato data = "" */
        return response()->json([
            'data' => [
                'totalNetWeight' => $totalNetWeight,
                'totalNetWeightByProducts' => $totalNetWeightByProducts
            ]
        ]);

    }
}

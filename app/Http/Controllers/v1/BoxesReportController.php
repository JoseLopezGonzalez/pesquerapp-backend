<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\BoxResource;
use App\Http\Resources\v1\PalletResource;
use App\Models\Box;
use App\Models\Order;
use App\Models\OrderPallet;
use App\Models\Pallet;
use App\Models\PalletBox;
use App\Models\StoredPallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BoxesReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /*return response()->json(['message' => 'Hola Mundo'], 200);*/
    /* return PalletResource::collection(Pallet::all()); */

    /*  public function index()
    {
        return PalletResource::collection(Pallet::paginate(10));

    } */

    public function index(Request $request)
    {

        //necesito filtrar pero las cajas de todos los palets que cumplan eso y no los palets en sí
        // para devolver un reporte de cajas y no de palets.

        $query = Box::query();

        if ($request->has('text')) {
            $text = $request->input('text');
            //Buscar en boxes si el pallet->id es igual a $text
            //pero en la tabla box no contiene pallet_id sino que existe una tabla llamada pallet_boxes
            //que contiene los id de las cajas y los id de los palets
            //por lo que necesito buscar en la tabla pallet_boxes
            $query->whereHas('pallet', function ($subQuery) use ($text) {
                $subQuery->where('id', 'like', "%{$text}%");
            });
        }

        
        if ($request->has('storeds') && $request->input('storeds') == 'on') {
            $query->whereHas('pallet', function ($subQuery) {
                $subQuery->where('state_id', 2);
            });
        }

        if ($request->has('shippeds') && $request->input('shippeds') == 'on') {
            $query->whereHas('pallet', function ($subQuery) {
                $subQuery->where('state_id', 3);
            });
        }

        if ($request->has('unlocateds') && $request->input('unlocateds') == 'on') {
            $query->whereHas('pallet', function ($subQuery) {
                $subQuery->whereHas('storedPallet', function ($subSubQuery) {
                    $subSubQuery->whereNull('position');
                });
            });
        }

        if ($request->has('locateds') && $request->input('locateds') == 'on') {
            $query->whereHas('pallet', function ($subQuery) {
                $subQuery->whereHas('storedPallet', function ($subSubQuery) {
                    $subSubQuery->whereNotNull('position');
                });
            });
        }

        /* Dates */

        if ($request->has('dates')) {
            $startDate = $request->input('dates')['start'];
            $endDate = $request->input('dates')['end'];

            // Asegúrate de ajustar las horas de inicio y fin para cubrir todo el día
            $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
            $endDate = date('Y-m-d 23:59:59', strtotime($endDate));

            $query->where('created_at', '>=', $startDate);
            $query->where('created_at', '<=', $endDate);
        }


        if ($request->has('observations')) {
            $observations = $request->input('observations');
            $query->whereHas('pallet', function ($subQuery) use ($observations) {
                $subQuery->where('observations', 'like', "%{$observations}%");
            });
        }

        if ($request->has('lots')) {
            $lots = $request->input('lots');
            $query->where('lot', 'like', "%{$lots}%");
        }

        if ($request->has('products')) {
            $articles = $request->input('products');
            $query->where('article_id', 'like', "%{$articles}%");
        }

        if ($request->has('weights')) {
            $weights = $request->input('weights');
            if (array_key_exists('netWeight', $weights)) {
                if (array_key_exists('min', $weights['netWeight'])) {
                    $query->havingRaw('sum(net_weight) >= ?', [$weights['netWeight']['min']]);
                }
                if (array_key_exists('max', $weights['netWeight'])) {
                    $query->havingRaw('sum(net_weight) <= ?', [$weights['netWeight']['max']]);
                }
            }
            if (array_key_exists('grossWeight', $weights)) {
                if (array_key_exists('min', $weights['grossWeight'])) {
                    $query->havingRaw('sum(gross_weight) >= ?', [$weights['grossWeight']['min']]);
                }
                if (array_key_exists('max', $weights['grossWeight'])) {
                    $query->havingRaw('sum(gross_weight) <= ?', [$weights['grossWeight']['max']]);
                }
            }
        }

        //Devolver boxes no palets



        

        
        return BoxResource::collection($query); 



        


    }

    
   
}

<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v2\OrderDetailsResource;
use App\Http\Resources\v2\OrderResource;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('active')) {
            if ($request->active == 'true') {
                /* where status is pending or loaddate>= today at the end of the day */
                return OrderResource::collection(Order::where('status', 'pending')->orWhereDate('load_date', '>=', now())->get());
            } else {
                /* where status is finished and loaddate< today at the end of the day */
                return OrderResource::collection(Order::where('status', 'finished')->whereDate('load_date', '<', now())->get());
            }
        } else {

            $query = Order::query();
            if ($request->has('customers')) {
                $query->whereIn('customer_id', $request->customers);
                /* $query->where('customer_id', $request->customer); */
            }

            /* $request->has('id') like id*/
            if ($request->has('id')) {
                $text = $request->id;
                $query->where('id', 'like', "%{$text}%");
            }

            /* ids */
            if ($request->has('ids')) {
                $query->whereIn('id', $request->ids);
            }

            /* buyerReference */
            if ($request->has('buyerReference')) {
                $text = $request->buyerReference;
                $query->where('buyer_reference', 'like', "%{$text}%");
            }

            /* status */
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            /* loadDate */
            if ($request->has('loadDate')) {
                $loadDate = $request->input('loadDate');
                /* Check if $loadDate['start'] exists */
                if (isset($loadDate['start'])) {
                    $startDate = $loadDate['start'];
                    $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
                    $query->where('load_date', '>=', $startDate);
                }
                /* Check if $loadDate['end'] exists */
                if (isset($loadDate['end'])) {
                    $endDate = $loadDate['end'];
                    $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
                    $query->where('load_date', '<=', $endDate);
                }
            }

            /* entryDate */
            if($request->has('entryDate')){
                $entryDate = $request->input('entryDate');
                if(isset($entryDate['start'])){
                    $startDate = $entryDate['start'];
                    $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
                    $query->where('entry_date', '>=', $startDate);
                }
                if(isset($entryDate['end'])){
                    $endDate = $entryDate['end'];
                    $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
                    $query->where('entry_date', '<=', $endDate);
                }
            }

            /* transports */
            if ($request->has('transports')) {
                $query->whereIn('transport_id', $request->transports);
                /* $query->where('customer_id', $request->customer); */
            }

            /* salespeople */
            if ($request->has('salespeople')) {
                $query->whereIn('salesperson_id', $request->salespeople);
                /* $query->where('customer_id', $request->customer); */
            }

            /* palletState */
            if ($request->has('palletsState')) {
                /* if order has any pallets */
                if($request->palletsState == 'stored'){
                    $query->whereHas('pallets', function ($q) use ($request) {
                        $q->where('state_id', 2);
                    });
                }else if ($request->palletsState == 'shipping'){
                    /* Solo tiene palets en el estado 3 */
                    $query->whereHas('pallets', function ($q) use ($request) {
                        $q->where('state_id', 3);
                    });
                }
            }

            /* incoterm */
            if ($request->has('incoterm')) {
                $query->where('incoterm_id', $request->incoterm);
            }

            /* Sort by date desc */
            $query->orderBy('load_date', 'desc');

            $perPage = $request->input('perPage', 10); // Default a 10 si no se proporciona
            return OrderResource::collection($query->paginate($perPage));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new OrderDetailsResource(Order::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json(['message' => 'Palet eliminado correctamente'], 200);
    }
}

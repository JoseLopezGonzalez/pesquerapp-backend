<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v2\OrderDetailsResource;
use App\Http\Resources\v2\OrderResource;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\OrderPlannedProductDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
            if ($request->has('entryDate')) {
                $entryDate = $request->input('entryDate');
                if (isset($entryDate['start'])) {
                    $startDate = $entryDate['start'];
                    $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
                    $query->where('entry_date', '>=', $startDate);
                }
                if (isset($entryDate['end'])) {
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
                if ($request->palletsState == 'stored') {
                    $query->whereHas('pallets', function ($q) use ($request) {
                        $q->where('state_id', 2);
                    });
                } else if ($request->palletsState == 'shipping') {
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

            /* transport */
            if ($request->has('transport')) {
                $query->where('transport_id', $request->transport);
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
    {
        $validated = $request->validate([
            'customer' => 'required|integer|exists:customers,id',
            'entryDate' => 'required|date',
            'loadDate' => 'required|date',
            'salesperson' => 'nullable|integer|exists:salespeople,id',
            'payment' => 'nullable|integer|exists:payment_terms,id',
            'incoterm' => 'nullable|integer|exists:incoterms,id',
            'buyerReference' => 'nullable|string',
            'transport' => 'nullable|integer|exists:transports,id',
            'truckPlate' => 'nullable|string',
            'trailerPlate' => 'nullable|string',
            'temperature' => 'nullable|string',
            'billingAddress' => 'nullable|string',
            'shippingAddress' => 'nullable|string',
            'transportationNotes' => 'nullable|string',
            'productionNotes' => 'nullable|string',
            'accountingNotes' => 'nullable|string',
            'emails' => 'nullable|array',
            'emails.*' => 'string|email:rfc,dns|distinct',
            'ccEmails' => 'nullable|array',
            'ccEmails.*' => 'string|email:rfc,dns|distinct',
            'plannedProducts' => 'nullable|array',
            'plannedProducts.*.product' => 'required|integer|exists:products,id',
            'plannedProducts.*.quantity' => 'required|numeric',
            'plannedProducts.*.boxes' => 'required|integer',
            'plannedProducts.*.unitPrice' => 'required|numeric',
            'plannedProducts.*.tax' => 'required|integer|exists:taxes,id',
        ]);

        // Formatear emails
        $allEmails = [];

        foreach ($validated['emails'] ?? [] as $email) {
            $allEmails[] = trim($email);
        }

        foreach ($validated['ccEmails'] ?? [] as $email) {
            $allEmails[] = 'CC:' . trim($email);
        }

        $formattedEmails = count($allEmails) > 0
            ? implode(";\n", $allEmails) . ';'
            : null;

        DB::beginTransaction();

        try {
            $order = Order::create([
                'customer_id' => $validated['customer'],
                'entry_date' => $validated['entryDate'],
                'load_date' => $validated['loadDate'],
                'salesperson_id' => $validated['salesperson'] ?? null,
                'payment_term_id' => $validated['payment'] ?? null,
                'incoterm_id' => $validated['incoterm'] ?? null,
                'buyer_reference' => $validated['buyerReference'] ?? null,
                'transport_id' => $validated['transport'] ?? null,
                'truck_plate' => $validated['truckPlate'] ?? null,
                'trailer_plate' => $validated['trailerPlate'] ?? null,
                'temperature' => $validated['temperature'] ?? null,
                'billing_address' => $validated['billingAddress'] ?? null,
                'shipping_address' => $validated['shippingAddress'] ?? null,
                'transportation_notes' => $validated['transportationNotes'] ?? null,
                'production_notes' => $validated['productionNotes'] ?? null,
                'accounting_notes' => $validated['accountingNotes'] ?? null,
                'emails' => $formattedEmails,
                'status' => 'pending',
            ]);

            if (!empty($validated['plannedProducts'])) {
                foreach ($validated['plannedProducts'] as $line) {
                    OrderPlannedProductDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $line['product'],
                        'tax_id' => $line['tax'],
                        'quantity' => $line['quantity'],
                        'boxes' => $line['boxes'],
                        'unit_price' => $line['unitPrice'],
                        'line_base' => $line['unitPrice'] * $line['quantity'],
                        'line_total' => $line['unitPrice'] * $line['quantity'],
                    ]);
                }
            }

            DB::commit();

            return new OrderDetailsResource($order);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear el pedido',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

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
    {
        $request->validate([
            'buyerReference' => 'sometimes|nullable|string',
            'paymentTerm' => 'sometimes|integer',
            'billingAddress' => 'sometimes|string',
            'shippingAddress' => 'sometimes|string',
            'transportationNotes' => 'sometimes|nullable|string',
            'productionNotes' => 'sometimes|nullable|string',
            'accountingNotes' => 'sometimes|nullable|string',
            'salesperson' => 'sometimes|integer',
            'emails' => 'sometimes|nullable|array',
            'emails.*' => 'string|email:rfc,dns|distinct',
            'ccEmails' => 'sometimes|nullable|array',
            'ccEmails.*' => 'string|email:rfc,dns|distinct',
            'transport' => 'sometimes|integer',
            'entryDate' => 'sometimes|date',
            'loadDate' => 'sometimes|date',
            'status' => 'sometimes|string',
            'incoterm' => 'sometimes|integer',
            'truckPlate' => 'sometimes|nullable|string',
            'trailerPlate' => 'sometimes|nullable|string',

            'temperature' => 'sometimes|nullable|numeric',
        ]);

        $order = Order::findOrFail($id);

        if ($request->has('buyerReference')) {
            $order->buyer_reference = $request->buyerReference;
        }
        if ($request->has('paymentTerm')) {
            $order->payment_term_id = $request->paymentTerm;
        }
        if ($request->has('billingAddress')) {
            $order->billing_address = $request->billingAddress;
        }
        if ($request->has('shippingAddress')) {
            $order->shipping_address = $request->shippingAddress;
        }
        if ($request->has('transportationNotes')) {
            $order->transportation_notes = $request->transportationNotes;
        }
        if ($request->has('productionNotes')) {
            $order->production_notes = $request->productionNotes;
        }
        if ($request->has('accountingNotes')) {
            $order->accounting_notes = $request->accountingNotes;
        }
        if ($request->has('salesperson')) {
            $order->salesperson_id = $request->salesperson;
        }
        if ($request->has('transport')) {
            $order->transport_id = $request->transport;
        }
        if ($request->has('entryDate')) {
            $order->entry_date = $request->entryDate;
        }
        if ($request->has('loadDate')) {
            $order->load_date = $request->loadDate;
        }
        if ($request->has('status')) {
            $order->status = $request->status;
        }
        if ($request->has('incoterm')) {
            $order->incoterm_id = $request->incoterm;
        }
        if ($request->has('truckPlate')) {
            $order->truck_plate = $request->truckPlate;
        }
        if ($request->has('trailerPlate')) {
            $order->trailer_plate = $request->trailerPlate;
        }
        if ($request->has('temperature')) {
            $order->temperature = $request->temperature;
        }

        // ✅ Convertir emails y ccEmails en texto plano
        if ($request->has('emails') || $request->has('ccEmails')) {
            $allEmails = [];

            foreach ($request->emails ?? [] as $email) {
                $allEmails[] = trim($email);
            }

            foreach ($request->ccEmails ?? [] as $email) {
                $allEmails[] = 'CC:' . trim($email);
            }

            $order->emails = count($allEmails) > 0
                ? implode(";\n", $allEmails) . ';'
                : null;
        }

        $order->updated_at = now();
        $order->save();

        return new OrderDetailsResource($order);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json(['message' => 'Palet eliminado correctamente'], 200);
    }

    /* Options */
    public function options()
    {
        $order = Order::select('id', 'id as name')
            ->orderBy('id')
            ->get();

        return response()->json($order);
    }

    /* Active Orders Options */
    public function activeOrdersOptions()
    {
        $orders = Order::where('status', 'pending')
            ->orWhereDate('load_date', '>=', now())
            ->select('id', 'id as name', 'load_date') // 👈 Aquí añado la fecha
            ->orderBy('load_date', 'desc') // 👈 Ordenar por fecha de carga
            ->get();

        return response()->json($orders);
    }


    /* update Order status */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();
        return new OrderDetailsResource($order);
    }


    public function orderRanking(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'groupBy' => 'required|in:client,country,product',
            'valueType' => 'required|in:totalAmount,totalQuantity',
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date',
            'speciesId' => 'nullable|integer|exists:species,id',
        ])->validate();

        $groupBy = $validated['groupBy'];
        $valueType = $validated['valueType'];
        $dateFrom = $validated['dateFrom'] . ' 00:00:00';
        $dateTo = $validated['dateTo'] . ' 23:59:59';
        $speciesId = $validated['speciesId'] ?? null;

        $orders = Order::with(['customer.country', 'pallets.boxes.box.product.species'])
            ->whereBetween('entry_date', [$dateFrom, $dateTo])
            ->get();

        $summary = [];

        foreach ($orders as $order) {
            $products = $order->product_details;

            if ($speciesId) {
                $products = array_filter($products, fn($p) => $p['product']['species_id'] === (int) $speciesId);
            }

            if (empty($products)) {
                continue;
            }

            foreach ($products as $p) {
                // 👇 Determinar nombre del grupo
                $groupName = match ($groupBy) {
                    'client' => $order->customer->name,
                    'country' => $order->customer->country->name ?? 'Sin país',
                    'product' => $p['product']['name'],
                };

                if (!isset($summary[$groupName])) {
                    $summary[$groupName] = [
                        'name' => $groupName,
                        'totalQuantity' => 0,
                        'totalAmount' => 0,
                    ];
                }

                $summary[$groupName]['totalQuantity'] += $p['netWeight'];
                $summary[$groupName]['totalAmount'] += $p['total'];
            }
        }

        $results = collect(array_values($summary))
            ->sortByDesc($valueType)
            ->values()
            ->map(fn($item) => [
                'name' => $item['name'],
                'value' => round($item[$valueType], 2),
            ]);

        return response()->json($results);
    }

    public function salesBySalesperson(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date',
        ])->validate();

        $dateFrom = $validated['dateFrom'] . ' 00:00:00';
        $dateTo = $validated['dateTo'] . ' 23:59:59';

        $orders = Order::with(['salesperson', 'pallets.boxes.box'])
            ->whereBetween('entry_date', [$dateFrom, $dateTo])
            ->get();

        $summary = [];

        foreach ($orders as $order) {
            $salespersonName = $order->salesperson->name ?? 'Sin comercial';

            if (!isset($summary[$salespersonName])) {
                $summary[$salespersonName] = 0;
            }

            foreach ($order->pallets as $pallet) {
                foreach ($pallet->boxes as $box) {
                    $summary[$salespersonName] += $box->netWeight;
                }
            }
        }

        $data = collect($summary)->map(function ($quantity, $name) {
            return [
                'name' => $name,
                'quantity' => round($quantity, 2),
            ];
        })->values();

        return response()->json($data);
    }

    /* Ojo, calcula la comparacion mismo rango de fechas pero un año atrás */
    public function totalQuantity(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date',
            'speciesId' => 'nullable|integer|exists:species,id',
        ])->validate();

        $dateFrom = $validated['dateFrom'] . ' 00:00:00';
        $dateTo = $validated['dateTo'] . ' 23:59:59';
        $speciesId = $validated['speciesId'] ?? null;

        $query = DB::table('boxes')
            ->join('pallets', 'boxes.pallet_id', '=', 'pallets.id')
            ->join('orders', 'pallets.order_id', '=', 'orders.id')
            ->join('products', 'boxes.product_id', '=', 'products.id')
            ->whereBetween('orders.entry_date', [$dateFrom, $dateTo]);

        if ($speciesId) {
            $query->where('products.species_id', $speciesId);
        }

        $totalQuantity = $query->sum('boxes.netWeight');

        return response()->json([
            'totalQuantity' => round($totalQuantity, 2),
        ]);
    }








}

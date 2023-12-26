<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return OrderResource::collection(Order::all()); 
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
        //Validación Con mensaje JSON

        $request->validate([
            'buyerReference' => 'sometimes|nullable|string',
            'customer.id' => 'required | integer',
            'paymentTerm.id' => 'required | integer',
            'billingAddress' => 'required | string',
            'shippingAddress' => 'required | string',
            'transportationNotes' => 'sometimes|nullable|string',
            'productionNotes' => 'sometimes|nullable|string',
            'accountingNotes' => 'sometimes|nullable|string',
            'salesperson.id' => 'required | integer',
            'emails' => 'sometimes|nullable|string',/* comprobar */
            'transport.id' => 'required | integer',
            'entryDate' => 'required | date',
            'loadDate' => 'required | date'
        ]);


        $order = Order::create($request->all());
        return response()->json($order, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new OrderResource(Order::findOrFail($id));
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
            'paymentTerm.id' => 'sometimes | integer',
            'billingAddress' => 'sometimes | string',
            'shippingAddress' => 'sometimes | string',
            'transportationNotes' => 'sometimes|nullable|string',
            'productionNotes' => 'sometimes|nullable|string',
            'accountingNotes' => 'sometimes|nullable|string',
            'salesperson.id' => 'sometimes | integer',
            'emails' => 'sometimes|nullable|string',/* comprobar */
            'transport.id' => 'sometimes | integer',
            'entryDate' => 'sometimes | date',
            'loadDate' => 'sometimes | date',
            'status' => 'sometimes | string'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'buyer_reference' => $request->buyerReference,
            'payment_term_id' => $request->paymentTerm['id'],
            'billing_address' => $request->billingAddress,
            'shipping_address' => $request->shippingAddress,
            'transportation_notes' => $request->transportationNotes,
            'production_notes' => $request->productionNotes,
            'accounting_notes' => $request->accountingNotes,
            'salesperson_id' => $request->salesperson['id'],
            'emails' => $request->emails,
            'transport_id' => $request->transport['id'],
            'entry_date' => $request->entryDate,
            'load_date' => $request->loadDate,
            'status' => $request->status
        ]);
        return response()->json($order, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

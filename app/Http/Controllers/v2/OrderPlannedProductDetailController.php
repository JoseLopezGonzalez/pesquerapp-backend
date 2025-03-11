<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v2\OrderPlannedProductDetailResource;
use App\Models\OrderPlannedProductDetail;
use Illuminate\Http\Request;

class OrderPlannedProductDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

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
            "boxes" => 'required|integer',
            "product.id" => 'required|integer|exists:products,id',
            "quantity" => 'required|numeric',
            "tax.id" => 'required|integer|exists:taxes,id',
            'unitPrice' => 'required|numeric',
        ]);

        $orderPlannedProductDetail = OrderPlannedProductDetail::findOrFail($id);
        $orderPlannedProductDetail->update([
            'product_id' => $request->product['id'],
            'tax_id' => $request->tax['id'],
            'quantity' => $request->quantity,
            'boxes' => $request->boxes,
            'unit_price' => $request->unitPrice,
            'line_base' => $request->unitPrice * $request->quantity,
            'line_total' => $request->unitPrice * $request->quantity,
        ]);

        return new OrderPlannedProductDetailResource($orderPlannedProductDetail);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $orderPlannedProductDetail = OrderPlannedProductDetail::findOrFail($id);
        $orderPlannedProductDetail->delete();
        return response()->json(['message' => 'Linea eliminada correctamente'], 200);
    }



}

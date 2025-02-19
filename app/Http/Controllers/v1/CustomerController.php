<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /* Sort by A-Z */
        return CustomerResource::collection(Customer::orderBy('name')->get());
        /* return CustomerResource::collection(Customer::all());  */
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /* Devolver todos los clientes solo id y name cuyo salesperson tenga id:8 y id:9 */
    public function autoSalesCustomers()
    {
        return CustomerResource::collection(Customer::whereHas('salesperson', function ($query) {
            $query->whereIn('id', [8, 9]);
        })->select('id', 'name')->get());
    }
}

<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CustomerResource;
use App\Http\Resources\v2\CustomerResource as V2CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        /* id */
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        /* ids */
        if ($request->has('ids')) {
            $query->whereIn('id', $request->ids);
        }

        /* name like */
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        /* vatNumber */
        if ($request->has('vatNumber')) {
            $query->where('vat_number', $request->vatNumber);
        }

        /* payentTerm where ir*/
        if ($request->has('paymentTerms')) {
            $query->whereIn('payment_term_id', $request->paymentTerms);
        }
        

        /* salespeople */
        if ($request->has('salespeople')) {
            $query->whereIn('salesperson_id', $request->salespeople);
        }

        /* country where ir */
        if ($request->has('countries')) {
            $query->whereIn('country_id', $request->countries);
        }
        
        /* order */
        $query->orderBy('name', 'asc');

        $perPage = $request->input('perPage', 10); // Default a 10 si no se proporciona
        return V2CustomerResource::collection($query->paginate($perPage));
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
        $customer = Customer::findOrFail($id);

        return new V2CustomerResource($customer);
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

    /**
     * Get all options for the customers select box.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        $customers = Customer::select('id', 'name') // Selecciona solo los campos necesarios
            ->orderBy('name', 'asc') // Ordena por nombre, opcional
            ->get();

        return response()->json($customers);
    }
}

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vatNumber' => 'nullable|string|max:20',
            'billing_address' => 'nullable|string|max:1000',
            'shipping_address' => 'nullable|string|max:1000',
            'transportation_notes' => 'nullable|string|max:1000',
            'production_notes' => 'nullable|string|max:1000',
            'accounting_notes' => 'nullable|string|max:1000',
            'emails' => 'nullable|array',
            'emails.*' => 'string|email:rfc,dns|distinct',
            'ccEmails' => 'nullable|array',
            'ccEmails.*' => 'string|email:rfc,dns|distinct',
            'contact_info' => 'nullable|string|max:1000',
            'salesperson_id' => 'nullable|exists:salespeople,id',
            'country_id' => 'nullable|exists:countries,id',
            'payment_term_id' => 'nullable|exists:payment_terms,id',
            'transport_id' => 'nullable|exists:transports,id',
            'a3erp_code' => 'nullable|string|max:255',
        ]);

        $allEmails = [];

        foreach ($validated['emails'] ?? [] as $email) {
            $allEmails[] = trim($email);
        }

        foreach ($validated['ccEmails'] ?? [] as $email) {
            $allEmails[] = 'CC:' . trim($email);
        }

        $validated['emails'] = count($allEmails) > 0
            ? implode(";\n", $allEmails) . ';'
            : null;

        unset($validated['ccEmails']); // Ya está incluido todo en 'emails'

        $customer = Customer::create($validated);

        return new V2CustomerResource($customer);
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
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json(['message' => 'Cliente eliminado con éxito']);
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => 'No se han proporcionado IDs válidos'], 400);
        }

        Customer::whereIn('id', $ids)->delete();

        return response()->json(['message' => 'Clientes eliminados con éxito']);
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

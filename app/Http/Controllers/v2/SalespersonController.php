<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v2\SalespersonResource;
use App\Models\Salesperson;
use Illuminate\Http\Request;

class SalespersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Salesperson::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('ids')) {
            $query->whereIn('id', $request->ids);
        }

        /* mame like */
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $query->orderBy('name', 'asc');

        $perPage = $request->input('perPage', 10);
        return SalespersonResource::collection($query->paginate($perPage));

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
            'emails' => 'nullable|array',
            'emails.*' => 'string|email:rfc,dns|distinct',
            'ccEmails' => 'nullable|array',
            'ccEmails.*' => 'string|email:rfc,dns|distinct',
        ]);

        // Combinar emails y ccEmails en el string único con separador ; y salto de línea
        $allEmails = [];

        foreach ($validated['emails'] ?? [] as $email) {
            $allEmails[] = trim($email);
        }

        foreach ($validated['ccEmails'] ?? [] as $ccEmail) {
            $allEmails[] = 'CC:' . trim($ccEmail);
        }

        $validated['emails'] = implode(";\n", $allEmails); // ← salto de línea después del ;

        unset($validated['ccEmails']); // ya están incluidos

        $salesperson = Salesperson::create($validated);

        return new SalespersonResource($salesperson);
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
    public function destroy(Salesperson $salesperson)
    {
        $salesperson->delete();
        return response()->json(['message' => 'Comercial eliminado con éxito.']);
    }



    public function destroyMultiple(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:salespeople,id',
        ]);

        Salesperson::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Comerciales eliminados correctamente.']);
    }



    public function options()
    {
        $salespeople = Salesperson::select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($salespeople);
    }
}

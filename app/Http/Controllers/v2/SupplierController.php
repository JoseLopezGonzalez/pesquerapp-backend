<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {

    }

    public function store(Request $request)
    {
       
    }

    public function show($id)
    {
       
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
        
    }

    /**
     * Get all options for the suppliers select box.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        $suppliers = Supplier::select('id', 'name') // Selecciona solo los campos necesarios
                       ->orderBy('name', 'asc') // Ordena por nombre, opcional
                       ->get();

        return response()->json($suppliers);
    }
}

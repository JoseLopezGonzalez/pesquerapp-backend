<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\SupplierResource;
use App\Http\Resources\v2\SupplierResource as V2SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('ids')) {
            $query->whereIn('id', $request->ids);
        }

       /* name like */
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }


        $query->orderBy('name', 'asc');

        $perPage = $request->input('perPage', 12); // Default a 10 si no se proporciona
        return V2SupplierResource::collection($query->paginate($perPage));
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

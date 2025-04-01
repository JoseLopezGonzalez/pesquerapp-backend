<?php

namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Store;
use App\Http\Resources\v1\StoreDetailsResource;
use App\Http\Resources\v1\StoreResource;
use App\Http\Resources\v2\StoreDetailsResource as V2StoreDetailsResource;
use App\Http\Resources\v2\StoreResource as V2StoreResource;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Store::query();

       /* filter by id */
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        /* filter by ids */
        if ($request->has('ids')) {
            $query->whereIn('id', $request->ids);
        }

        /* filter by name */
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        /* ORDER */
        $query->orderBy('name' , 'asc');

        $perPage = $request->input('perPage', 12); // Default a 10 si no se proporciona
        return V2StoreResource::collection($query->paginate($perPage));


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
        return new V2StoreDetailsResource(Store::find($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       
    }

    /* Options */
    public function options()
    {
        $store = Store::select('id', 'name')
        ->orderBy('id')
        ->get();

        return response()->json($store);
    }
}

<?php

namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Store;
use App\Http\Resources\v1\StoreDetailsResource;
use App\Http\Resources\v1\StoreResource;
use App\Http\Resources\v2\StoreDetailsResource as V2StoreDetailsResource;
use App\Http\Resources\v2\StoreResource as V2StoreResource;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{

    private function getDefaultMap(): array
    {
        return [
            "posiciones" => [
                [
                    "id" => 1,
                    "nombre" => "U1",
                    "x" => 40,
                    "y" => 40,
                    "width" => 460,
                    "height" => 238,
                    "tipo" => "center",
                    "nameContainer" => [
                        "x" => 0,
                        "y" => 0,
                        "width" => 230,
                        "height" => 180
                    ],
                ]
            ],
            "elementos" => [
                "fondos" => [
                    [
                        "x" => 0,
                        "y" => 0,
                        "width" => 3410,
                        "height" => 900
                    ]
                ],
                "textos" => []
            ]
        ];
    }

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
        $query->orderBy('name', 'asc');

        $perPage = $request->input('perPage', 12); // Default a 10 si no se proporciona
        return V2StoreResource::collection($query->paginate($perPage));


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'temperature' => 'required|string|max:255',
            'capacity' => 'required|numeric|min:0',
        ]);

        $validated['map'] = json_encode($this->getDefaultMap());

        $store = Store::create($validated);

        return response()->json([
            'message' => 'Almacén creado correctamente',
            'data' => new V2StoreResource($store),
        ], 201);
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
        $store = Store::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'temperature' => 'required|string|max:255',
            'capacity' => 'required|numeric|min:0',
        ]);

        $store->update($validated);

        return response()->json([
            'message' => 'Almacén actualizado correctamente',
            'data' => new V2StoreResource($store),
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $store = Store::findOrFail($id);
        $store->delete();

        return response()->json(['message' => 'Almacén eliminado correctamente.']);
    }

    public function deleteMultiple(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:stores,id',
        ]);

        Store::whereIn('id', $validated['ids'])->delete();

        return response()->json(['message' => 'Almacenes eliminados correctamente.']);
    }



    /* Options */
    public function options()
    {
        $store = Store::select('id', 'name')
            ->orderBy('id')
            ->get();

        return response()->json($store);
    }

     public function totalStock()
    {
        $totalStock = DB::table('pallets')
            ->join('pallet_boxes', 'pallet_boxes.pallet_id', '=', 'pallets.id')
            ->join('boxes', 'boxes.id', '=', 'pallet_boxes.box_id')
            ->where('pallets.state_id', 2) // solo palets almacenados
            ->sum('boxes.net_weight');

        return response()->json([
            'totalStock' => round($totalStock, 2),
        ]);
    }
}

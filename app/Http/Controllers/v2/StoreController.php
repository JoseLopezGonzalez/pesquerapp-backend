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


    public function totalStockBySpecies()
    {
        $inventory = \App\Models\StoredPallet::all();
        $species = \App\Models\Species::all();
        $speciesInventory = [];

        foreach ($species as $specie) {
            $totalNetWeight = 0;

            foreach ($inventory as $storedPallet) {
                foreach ($storedPallet->pallet->boxes as $palletBox) {
                    $box = $palletBox->box;

                    // Cuidado: acceder a product->species porque article->species no está bien enlazado
                    if ($box->product && $box->product->species && $box->product->species->id == $specie->id) {
                        $totalNetWeight += $box->net_weight;
                    }
                }
            }

            if ($totalNetWeight == 0) {
                continue;
            }

            $speciesInventory[] = [
                'id' => $specie->id,
                'name' => $specie->name,
                'total_kg' => round($totalNetWeight, 2),
            ];
        }

        // Calcular total global
        $totalKg = array_sum(array_column($speciesInventory, 'total_kg'));

        // Calcular porcentaje para cada especie
        foreach ($speciesInventory as &$item) {
            $item['percentage'] = $totalKg > 0
                ? round(($item['total_kg'] / $totalKg) * 100, 2)
                : 0;
        }

        // Ordenar por total_kg descendente
        usort($speciesInventory, fn($a, $b) => $b['total_kg'] <=> $a['total_kg']);

        return response()->json($speciesInventory);
    }

    public function totalStockByProducts()
    {
        $inventory = \App\Models\StoredPallet::all();
        $products = \App\Models\Product::with('article')->get();

        $productsInventory = [];

        foreach ($products as $product) {
            $totalNetWeight = 0;

            foreach ($inventory as $storedPallet) {
                foreach ($storedPallet->pallet->boxes as $palletBox) {
                    if ($palletBox->box->product->id == $product->id) {
                        $totalNetWeight += $palletBox->box->net_weight;
                    }
                }
            }

            if ($totalNetWeight == 0) {
                continue;
            }

            $productsInventory[] = [
                'id' => $product->id,
                'name' => $product->article->name,
                'total_kg' => round($totalNetWeight, 2),
            ];
        }

        // Calcular total global
        $totalNetWeight = array_sum(array_column($productsInventory, 'total_kg'));

        // Añadir porcentajes
        foreach ($productsInventory as &$productInventory) {
            $productInventory['percentage'] = round(($productInventory['total_kg'] / $totalNetWeight) * 100, 2);
        }

        // Ordenar descendente por total_kg
        usort($productsInventory, function ($a, $b) {
            return $b['total_kg'] <=> $a['total_kg'];
        });

        return response()->json($productsInventory);
    }



}

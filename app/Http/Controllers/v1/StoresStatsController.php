<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Species;
use App\Models\StoredPallet;
use Illuminate\Http\Request;

class StoresStatsController extends Controller
{
    /* Get Inventario total de todos los almacenes agrupados por especies , para ello tendremos que buscar por pesoNeto de cada caja que este ubicado en algun almacen, 
    las cajas no tienen directamente vinculación con un almacen, por el contrario son los palets que contienen cajas quienes estan vinculados con un almacen */
    public function totalInventoryBySpecies()
    {
        $inventory = StoredPallet::with('boxes')->get();
        $species = Species::all();
        $speciesInventory = [];
        foreach ($species as $specie) {
            $totalWeight = 0;
            foreach ($inventory as $pallet) {
                foreach ($pallet->boxes as $box) {
                    if ($box->product->specie_id == $specie->id) {
                        $totalWeight += $box->netWeight;
                    }
                }
            }
            $speciesInventory[] = [
                'specie' => $specie->name,
                'totalWeight' => $totalWeight
            ];
        }
        return response()->json($speciesInventory);
    }
}

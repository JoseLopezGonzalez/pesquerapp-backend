<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Species;
use App\Models\StoredPallet;
use Illuminate\Http\Request;

class StoresStatsController extends Controller
{
    /* Get Inventario total de todos los almacenes agrupados por especies , para ello tendremos que buscar por pesoNeto de cada caja que este ubicado en algun almacen, 
    las cajas no tienen directamente vinculación con un almacen, por el contrario son los palets que contienen cajas quienes estan vinculados con un almacen */
    public function totalInventoryBySpecies()
    {

        /* StoredPallet no tiene boxes, tiene pallet que a su vez tiene  */
        /* $inventory = StoredPallet::with('boxes')->get(); */
        /* No hace falta el with  */
        $inventory = StoredPallet::all();


        $species = Species::all();
        $speciesInventory = [];
        foreach ($species as $specie) {
            $totalNetWeight = 0;
            foreach ($inventory as $storedPallet) {
                foreach ($storedPallet->pallet->boxes as $palletBox) {
                    if ($palletBox->box->product->species->id == $specie->id) {
                        $totalNetWeight += $palletBox->box->net_weight;
                    }
                }
            }

            
            if($totalNetWeight == 0){
                continue;
            }

            $speciesInventory[] = [
                'name' => $specie->name,
                'totalNetWeight' => $totalNetWeight,
                
                
            ];
        }

        /* TotalNetWeight en global */
        $totalNetWeight = 0;
        foreach ($speciesInventory as $specieInventory) {
            $totalNetWeight += $specieInventory['totalNetWeight'];
        }

        /* Añadir porcentaje */

        foreach ($speciesInventory as &$specieInventory) {
            $specieInventory['percentage'] = $specieInventory['totalNetWeight'] / $totalNetWeight * 100;
        }

        



        return response()->json([
            'data' => [
                'totalNetWeight' => $totalNetWeight,
                'speciesInventory' => $speciesInventory,
            ]
        ]);
    }


    /* Get inventario total de todos los almacenes agrupados por productos */
    public function totalInventoryByProducts()
    {
        $inventory = StoredPallet::all();
        $products = Product::all();
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

            if($totalNetWeight == 0){
                continue;
            }

            $productsInventory[] = [
                'name' => $product->article->name,
                'totalNetWeight' => $totalNetWeight,
            ];
        }

        /* TotalNetWeight en global */
        $totalNetWeight = 0;
        foreach ($productsInventory as $productInventory) {
            $totalNetWeight += $productInventory['totalNetWeight'];
        }

        /* Añadir porcentaje */

        foreach ($productsInventory as &$productInventory) {
            $productInventory['percentage'] = $productInventory['totalNetWeight'] / $totalNetWeight * 100;
        }

        return response()->json([
            'data' => [
                'totalNetWeight' => $totalNetWeight,
                'productsInventory' => $productsInventory,
            ]
        ]);
    }

}

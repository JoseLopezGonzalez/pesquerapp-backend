<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Species;
use App\Models\StoredPallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StoresStatsController extends Controller
{
    /* Get Inventario total de todos los almacenes agrupados por especies , para ello tendremos que buscar por pesoNeto de cada caja que este ubicado en algun almacen, 
    las cajas no tienen directamente vinculación con un almacen, por el contrario son los palets que contienen cajas quienes estan vinculados con un almacen */
    public function totalInventoryBySpecies()
    {

        // Obtener los pesos netos agrupados por especie
        $speciesInventory = DB::table('species')
            ->join('products', 'species.id', '=', 'products.species_id')
            ->join('boxes', 'products.id', '=', 'boxes.product_id')
            ->join('pallet_boxes', 'boxes.id', '=', 'pallet_boxes.box_id')
            ->join('pallets', 'pallet_boxes.pallet_id', '=', 'pallets.id')
            ->join('stored_pallets', 'pallets.id', '=', 'stored_pallets.pallet_id')
            ->select('species.name', DB::raw('SUM(boxes.net_weight) as totalNetWeight'))
            ->groupBy('species.name')
            ->havingRaw('SUM(boxes.net_weight) > 0')
            ->get();

        // Calcular el total global de peso neto
        $totalNetWeight = $speciesInventory->sum('totalNetWeight');

        // Añadir el porcentaje a cada especie
        $speciesInventory->transform(function ($specieInventory) use ($totalNetWeight) {
            $specieInventory->percentage = ($specieInventory->totalNetWeight / $totalNetWeight) * 100;
            return $specieInventory;
        });

        return response()->json([
            'data' => [
                'totalNetWeight' => $totalNetWeight,
                'speciesInventory' => $speciesInventory,
            ]
        ]);

        /* StoredPallet no tiene boxes, tiene pallet que a su vez tiene  */
        /* $inventory = StoredPallet::with('boxes')->get(); */
        /* No hace falta el with  */
        /* $inventory = StoredPallet::all();


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
        } */

        /* TotalNetWeight en global */
        /* $totalNetWeight = 0;
        foreach ($speciesInventory as $specieInventory) {
            $totalNetWeight += $specieInventory['totalNetWeight'];
        }
 */
        /* Añadir porcentaje */

        /* foreach ($speciesInventory as &$specieInventory) {
            $specieInventory['percentage'] = $specieInventory['totalNetWeight'] / $totalNetWeight * 100;
        }

        



        return response()->json([
            'data' => [
                'totalNetWeight' => $totalNetWeight,
                'speciesInventory' => $speciesInventory,
            ]
        ]); */
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

            if ($totalNetWeight == 0) {
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

        /* Sort by netWeight */
        usort($productsInventory, function ($a, $b) {
            return $b['totalNetWeight'] - $a['totalNetWeight'];
        });

        return response()->json([
            'data' => [
                'totalNetWeight' => $totalNetWeight,
                'productsInventory' => $productsInventory,
            ]
        ]);
    }
}

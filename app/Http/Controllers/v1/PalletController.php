<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\PalletResource;
use App\Models\Box;
use App\Models\Pallet;
use App\Models\PalletBox;
use App\Models\StoredPallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /*return response()->json(['message' => 'Hola Mundo'], 200);*/
        return PalletResource::collection(Pallet::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //Validación Sin mensaje JSON
        /* $request->validate([
            'observations' => 'required|string',
            'boxes' => 'required|array',
            'boxes.*.article.id' => 'required|integer',
            'boxes.*.lot' => 'required|string',
            'boxes.*.gs1128' => 'required|string',
            'boxes.*.grossWeight' => 'required|numeric',
            'boxes.*.netWeight' => 'required|numeric',
        ]); */

        //Validación Con mensaje JSON
        $validator = Validator::make($request->all(), [
            'observations' => 'nullable|string',
            'boxes' => 'required|array',
            'boxes.*.article.id' => 'required|integer',
            'boxes.*.lot' => 'required|string',
            'boxes.*.gs1128' => 'required|string',
            'boxes.*.grossWeight' => 'required|numeric',
            'boxes.*.netWeight' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Código de estado 422 - Unprocessable Entity
        }


        $pallet = $request->all();
        $boxes = $pallet['boxes'];


        //Insertando Palet
        $newPallet = new Pallet;
        $newPallet->observations = $pallet['observations'];
        $newPallet->state_id = 1; // Siempre estado registrado.
        $newPallet->save();

        //Insertando Cajas
        foreach ($boxes as $box) {
            $newBox = new Box;
            $newBox->article_id = $box['article']['id'];
            $newBox->lot = $box['lot'];
            $newBox->gs1_128 = $box['gs1128'];
            $newBox->gross_weight = $box['grossWeight'];
            $newBox->net_weight = $box['netWeight'];
            $newBox->save();

            //Agregando Cajas a Palet
            $newPalletBox = new PalletBox;
            $newPalletBox->pallet_id = $newPallet->id;
            $newPalletBox->box_id = $newBox->id;
            $newPalletBox->save();
        }

        return response()->json($newPallet->toArrayAssoc(), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new PalletResource(Pallet::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'observations' => 'sometimes|nullable|string',
            'store' => 'sometimes|nullable|integer',
            'state.id' => 'sometimes|integer',
            'boxes' => 'sometimes|array',
            'boxes.*.id' => 'sometimes|nullable|integer',
            'boxes.*.article.id' => 'required_with:boxes|integer',
            'boxes.*.lot' => 'required_with:boxes|string',
            'boxes.*.gs1128' => 'required_with:boxes|string',
            'boxes.*.grossWeight' => 'required_with:boxes|numeric',
            'boxes.*.netWeight' => 'required_with:boxes|numeric',
        ]);

        //Cuidado con cambiar validación en la opcion de cambiar a enviado un palet

        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Código de estado 422 - Unprocessable Entity
        }

        $pallet = $request->all();

        //Creating Pallet
        $updatedPallet = Pallet::find($id);

        //Updating State
        if ($request->has('state')) {
            echo '$updatedPallet->state_id = '.$updatedPallet->state_id . '!= $pallet[state][id] = '.$pallet["state"]["id"];
            if ($updatedPallet->state_id != $pallet['state']['id']) {
                // UnStoring pallet if it is in a store
                echo '$updatedPallet->store ='. $updatedPallet->store. '!= null && $pallet[state][id] ='.$pallet['state']['id'].' != 2';
                if($updatedPallet->store != null && $pallet['state']['id'] != 2){
                    $updatedPallet->unStore();
                    //return response()->json(['errors' => ['state' => ['El palet se encuentra en un almacen, no se puede cambiar el estado']]], 422);
                }
                $updatedPallet->state_id = $pallet['state']['id'];
            }
        }

        //Updating Observations
        if ($request->has('observations')){
            if($pallet['observations'] != $updatedPallet->observations){
                $updatedPallet->observations = $pallet['observations'];
            }
        }

        $updatedPallet->save();

        // Updating Store
        if (array_key_exists("store", $pallet)) {
            $storeId = $pallet['store'];

            $isPalletStored = StoredPallet::where('pallet_id', $updatedPallet->id)->first();
            if ($isPalletStored) {
                if ($isPalletStored->store_id != $storeId) {
                    $isPalletStored->delete();
                    if ($storeId) {
                        //Agregando Palet a almacen
                        $newStoredPallet = new StoredPallet;
                        $newStoredPallet->pallet_id = $updatedPallet->id;
                        $newStoredPallet->store_id = $storeId;
                        $newStoredPallet->save();
                    }
                }
            } else {
                if ($storeId) {
                    //Agregando Palet a almacen
                    $newStoredPallet = new StoredPallet;
                    $newStoredPallet->pallet_id = $updatedPallet->id;
                    $newStoredPallet->store_id = $storeId;
                    $newStoredPallet->save();
                }
            }
        }

        //Updating Boxes
        if (array_key_exists("boxes", $pallet)) {
            $boxes = $pallet['boxes'];

            //Eliminando Cajas y actualizando
            $updatedPallet->boxes->map(function ($box) use (&$boxes) {

                $hasBeenUpdated = false;

                foreach ($boxes as $index => $updatedBox) {
                    if ($updatedBox['id'] == $box->box->id) {
                        
                        $box->box->article_id = $updatedBox['article']['id'];
                        $box->box->lot = $updatedBox['lot'];
                        $box->box->gs1_128 = $updatedBox['gs1128'];
                        $box->box->gross_weight = $updatedBox['grossWeight'];
                        $box->box->net_weight = $updatedBox['netWeight'];
                        $box->box->save();
                        $hasBeenUpdated = true;
                        //Eliminando Caja del array para añadir
                        unset($boxes[$index]);
                    }
                }

                if (!$hasBeenUpdated) {
                    $box->box->delete();
                }
            });

            $boxes = array_values($boxes);


            //Insertando Cajas
            foreach ($boxes as $box) {
                $newBox = new Box;
                $newBox->article_id = $box['article']['id'];
                $newBox->lot = $box['lot'];
                $newBox->gs1_128 = $box['gs1128'];
                $newBox->gross_weight = $box['grossWeight'];
                $newBox->net_weight = $box['netWeight'];
                $newBox->save();

                //Agregando Cajas a Palet
                $newPalletBox = new PalletBox;
                $newPalletBox->pallet_id = $updatedPallet->id;
                $newPalletBox->box_id = $newBox->id;
                $newPalletBox->save();
            }
        }

        $updatedPallet->refresh();

        return response()->json($updatedPallet->toArrayAssoc(), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pallet = Pallet::findOrFail($id);
        $pallet->delete();

        return response()->json(['message' => 'Palet eliminado correctamente'], 200);
    }
}

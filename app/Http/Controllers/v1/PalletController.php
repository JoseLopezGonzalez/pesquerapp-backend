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
            'observations' => 'nullable|string',
            'storeId' => 'nullable|integer',
            'boxes' => 'required|array',
            'boxes.*.id' => 'required|integer',
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
        //dd($pallet);

        $boxes = $pallet['boxes'];
        $storeId = $pallet['storeId'];



        //Insertando Palet
        $updatedPallet = Pallet::find($id);
        //Validar que encuentre algo
        $updatedPallet->observations = $pallet['observations'];
        $updatedPallet->save();

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
        }else{
            if ($storeId) {
                //Agregando Palet a almacen
                $newStoredPallet = new StoredPallet;
                $newStoredPallet->pallet_id = $updatedPallet->id;
                $newStoredPallet->store_id = $storeId;
                $newStoredPallet->save();
            }
        }



        //Eliminando Cajas y actualizando
        $updatedPallet->boxes->map(function ($box) use (&$boxes) {

            $hasBeenUpdated = false;

            foreach ( $boxes as $index => $updatedBox){
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

        $palletTEST = Pallet::find($updatedPallet->id);

        return response()->json($palletTEST->toArrayAssoc(), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pallet = Pallet::find($id);
        $pallet->boxes->map(function ($box) {
            $box->box->delete();
        });
        $pallet->delete();

        return response()->json(['message' => 'Palet eliminado correctamente'], 200);
    }
}

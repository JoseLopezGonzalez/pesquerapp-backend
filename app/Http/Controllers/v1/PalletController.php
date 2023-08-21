<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\PalletResource;
use App\Models\Box;
use App\Models\Pallet;
use App\Models\PalletBox;
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
            'storeId' => 'required|numeric',
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
        $storeId = $pallet['storeId'];

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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
        $updatedPallet = Pallet::find($id);
        $updatedPallet->observations = $pallet['observations'];
        $updatedPallet->save();

        //Eliminando Cajas
        $updatedPallet->boxes->map(function ($box) {
            $box->box->delete();
        });

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
        //
    }
}

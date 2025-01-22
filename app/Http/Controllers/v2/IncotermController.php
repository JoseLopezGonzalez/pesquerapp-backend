<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\TransportResource;
use App\Http\Resources\v2\IncotermResource;
use App\Models\Incoterm;
use App\Models\Transport;
use Illuminate\Http\Request;

class IncotermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $query = Incoterm::query();

        if (request()->has('id')) {
            $query->where('id', request()->id);
        }

        if (request()->has('ids')) {
            $query->whereIn('id', request()->ids);
        }

        /* order */
        $query->orderBy('code', 'asc');

        $perPage = request()->input('perPage', 10); // Default a 10 si no se proporciona
        return IncotermResource::collection($query->paginate($perPage));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get all options for the incoterms select box.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        $incoterms = Incoterm::select('id', 'code' , 'description') // Selecciona solo los campos necesarios
                       ->get();

        $incoterms = $incoterms->map(function ($incoterm) {
            return [
                'id' => $incoterm->id,
                'name' => "{$incoterm->code} - {$incoterm->description}"
            ];
        });


        return response()->json($incoterms);
    }
}

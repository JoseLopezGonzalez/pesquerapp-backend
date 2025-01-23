<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\TransportResource;
use App\Http\Resources\v2\BoxResource;
use App\Http\Resources\v2\TransportResource as V2TransportResource;
use App\Models\Box;
use App\Models\Transport;
use Illuminate\Http\Request;

class BoxesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = Box::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('ids')) {
            $query->whereIn('id', $request->ids);
        }

        /*  product.article.name*/
        if ($request->has('name')) {
            $query->whereHas('product', function ($query) use ($request) {
                $query->whereHas('article', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->name . '%');
                });
            });
        }

        /* product.species Where in*/
        if ($request->has('species')) {
            $query->whereHas('product', function ($query) use ($request) {
                $query->whereIn('species_id', $request->species);
            });
        }

        /* lot where in*/
        if ($request->has('lots')) {
            $query->whereIn('lot', $request->lots);
        }

        /* products where in */
        if ($request->has('products')) {
            $query->whereIn('article_id', $request->products);
        }

        /* palletIds */
        if ($request->has('pallets')) {
            $query->whereHas('palletBox', function ($query) use ($request) {
                $query->whereIn('pallet_id', $request->pallets);
            });
        }

        /* gs1128 where in */
        if ($request->has('gs1128')) {
            $query->whereIn('gs1_128', $request->gs1128);
        }

        /* createdAt */
        if ($request->has('createdAt')) {
            $createdAt = $request->input('createdAt');
            /* Check if $createdAt['start'] exists */
            if (isset($createdAt['start'])) {
                $startDate = $createdAt['start'];
                $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
                $query->where('created_at', '>=', $startDate);
            }
            /* Check if $createdAt['end'] exists */
            if (isset($createdAt['end'])) {
                $endDate = $createdAt['end'];
                $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
                $query->where('created_at', '<=', $endDate);
            }
        }


        /* order by id desc */
        $query->orderBy('id', 'desc');

        /* no filter more */
        $perPage = $request->input('perPage', 12); // Default a 10 si no se proporciona
        return BoxResource::collection($query->paginate($perPage));




       
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

    
}

<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\SalespersonResource;
use App\Models\Salesperson;
use Illuminate\Http\Request;

class SalespersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Salesperson::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('ids')) {
            $query->whereIn('id', $request->ids);
        }

        if ($request->has('names')) {
            $query->whereIn('name', $request->names);
        }

        $query->orderBy('name', 'asc');

        $perPage = $request->input('perPage', 10);
        return SalespersonResource::collection($query->paginate($perPage));

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


    public function options()
    {
        $salespeople = Salesperson::select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($salespeople);
    }
}

<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CeboResource;
use App\Models\Cebo;
use Illuminate\Http\Request;

class CeboController extends Controller
{
    public function index(Request $request)
    {
        $query = Cebo::query()->with('product.article');

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('fixed')) {
            $query->where('fixed', $request->fixed);
        }

        $perPage = $request->input('perPage', 12); // Default a 12 si no se proporciona
        return CeboResource::collection($query->paginate($perPage));
    }
}

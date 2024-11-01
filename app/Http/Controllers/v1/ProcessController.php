<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ProcessResource;
use App\Models\Process;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public function index(Request $request)
    {
        $query = Process::query();

        // Filtrar por tipo si se proporciona en la solicitud
        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        // Devolver la colección de ProcessResource
        return ProcessResource::collection($query->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:starting,process,final'
        ]);

        return Process::create($request->all());
    }

    public function show(Process $process)
    {
        return $process;
    }

    public function update(Request $request, Process $process)
    {
        $request->validate([
            'name' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:starting,process,final'
        ]);

        $process->update($request->all());

        return $process;
    }

    public function destroy(Process $process)
    {
        $process->delete();

        return response()->json(['message' => 'Process deleted successfully.']);
    }
}

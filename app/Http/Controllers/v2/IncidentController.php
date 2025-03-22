<?php


namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        return Incident::with('order')->latest()->get();
    }

    public function show(Incident $incident)
    {
        return response()->json($incident->load('order'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'description' => 'required|string',
        ]);

        $incident = Incident::create($validated);

        // Al crear la incidencia, el pedido pasa a estado 'incident'
        $incident->order->update(['status' => 'incident']);

        return response()->json($incident, 201);
    }

    public function update(Request $request, Incident $incident)
    {
        $validated = $request->validate([
            'resolution_type' => 'required|in:returned,partially_returned,compensated',
            'resolution_notes' => 'nullable|string',
        ]);

        $incident->update([
            'status' => 'resolved',
            'resolution_type' => $validated['resolution_type'],
            'resolution_notes' => $validated['resolution_notes'] ?? null,
            'resolved_at' => now(),
        ]);

        return response()->json($incident);
    }

    public function destroy(Incident $incident)
    {
        $incident->delete();
        return response()->noContent();
    }
}

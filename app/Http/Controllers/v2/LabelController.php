<?php
namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v2\LabelResource;
use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index()
    {
        return LabelResource::collection(Label::orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'format' => 'nullable|array',
        ]);

        $label = Label::create($validated);
        return new LabelResource($label);
    }

    public function show(Label $label)
    {
        return new LabelResource($label);
    }

    public function update(Request $request, Label $label)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'format' => 'nullable|array',
        ]);

        $label->update($validated);
        return new LabelResource($label);
    }

    public function destroy(Label $label)
    {
        $label->delete();
        /* Devolver mensaje satisfactorio o error */
        return response()->json([
            'message' => 'Etiqueta eliminada correctamente.'
        ], 200);

    }


    /* Labels options */
    public function options()
    {
        $labels = Label::orderBy('name')->get();
        return response()->json(
            $labels->map(function ($label) {
                return [
                    'id' => $label->id,
                    'name' => $label->name,
                ];
            }),
        );
    }

    /* Destroy by id */
    /* public function destroy($id)
    {
        $label = Label::findOrFail($id);
        $label->delete();
        return response()->noContent();
    } */
}

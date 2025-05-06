<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CeboDispatchResource;
use App\Models\CeboDispatch;
use App\Models\CeboDispatchProduct;
use App\Models\Supplier;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CeboDispatchController extends Controller
{
    public function index(Request $request)
    {
        $query = CeboDispatch::query();
        $query->with('supplier', 'products.product');

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('suppliers')) {
            $query->whereIn('supplier_id', $request->suppliers);
        }

        if ($request->has('dates')) {
            $query->whereBetween('date', [$request->dates['start'], $request->dates['end']]);
        }

        if ($request->has('species')) {
            $query->whereHas('products.product', function ($query) use ($request) {
                $query->whereIn('species_id', $request->species);
            });
        }

        if ($request->has('products')) {
            $query->whereHas('products.product', function ($query) use ($request) {
                $query->whereIn('id', $request->products);
            });
        }

        if ($request->has('notes')) {
            $query->where('notes', 'like', '%' . $request->notes . '%');
        }

        /* Order by Date Descen */
        $query->orderBy('date', 'desc');

        $perPage = $request->input('perPage', 12); // Default a 10 si no se proporciona
        return CeboDispatchResource::collection($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier.id' => 'required',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'exportType' => 'nullable|in:facilcom,a3erp', // ✅ NUEVO
            'details' => 'required|array',
            'details.*.product.id' => 'required|exists:products,id',
            'details.*.netWeight' => 'required|numeric',
            'details.*.price' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Código de estado 422 - Unprocessable Entity
        }

        $dispatch = new CeboDispatch();
        $dispatch->supplier_id = $request->supplier['id'];
        $dispatch->date = $request->date;

        if ($request->has('notes')) {
            $dispatch->notes = $request->notes;
        }

        $supplier = Supplier::findOrFail($request->supplier['id']);
        $dispatch->export_type = $request->input('exportType', $supplier->cebo_export_type ?? 'facilcom');

        /* $dispatch->export_type = $request->input('exportType', $dispatch->supplier->cebo_export_type ?? 'facilcom'); */


        $dispatch->save();

        if ($request->has('details')) {
            foreach ($request->details as $detail) {
                $price = $detail['price'] ?? null;
                if ($price === null) {
                    $price = $this->getLastPrice($dispatch->supplier_id, $detail['product']['id']);
                }

                $dispatch->products()->create([
                    'product_id' => $detail['product']['id'],
                    'net_weight' => $detail['netWeight'],
                    'price' => $price,
                ]);
            }

        }

        return new CeboDispatchResource($dispatch);
    }

    public function show($id)
    {
        $dispatch = CeboDispatch::with('supplier', 'products.product')->findOrFail($id);
        return new CeboDispatchResource($dispatch);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'supplier.id' => 'required',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'exportType' => 'nullable|in:facilcom,a3erp', // ✅ NUEVO
            'details' => 'required|array',
            'details.*.product.id' => 'required|exists:products,id',
            'details.*.netWeight' => 'required|numeric',
            'details.*.price' => 'nullable|numeric',

        ]);

        $dispatch = CeboDispatch::findOrFail($id);
        $dispatch->update([
            'supplier_id' => $validated['supplier']['id'],
            'date' => $validated['date'],
            'notes' => $validated['notes'],
            'export_type' => $validated['exportType'] ?? 'facilcom', // ✅ NUEVO

        ]);

        $dispatch->products()->delete();
        foreach ($validated['details'] as $detail) {
            $price = $detail['price'] ?? null;
            if ($price === null) {
                $price = $this->getLastPrice($dispatch->supplier_id, $detail['product']['id']);
            }

            $dispatch->products()->create([
                'product_id' => $detail['product']['id'],
                'net_weight' => $detail['netWeight'],
                'price' => $price,
            ]);
        }


        return new CeboDispatchResource($dispatch);
    }

    public function destroy($id)
    {
        $dispatch = CeboDispatch::findOrFail($id);
        $dispatch->delete();
        return response()->json(['message' => 'Despacho de cebo eliminado correctamente'], 200);
    }

    private function getLastPrice($supplierId, $productId)
    {
        return CeboDispatchProduct::whereHas('dispatch', function ($query) use ($supplierId) {
            $query->where('supplier_id', $supplierId);
        })
            ->where('product_id', $productId)
            ->whereNotNull('price')
            ->orderByDesc('created_at')
            ->value('price'); // Devuelve solo el último precio
    }

}

<?php

namespace App\Exports\v1;

use App\Models\Box;
use App\Models\CeboDispatch;
use App\Models\RawMaterialReception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class CeboDispatchFacilcomExport implements FromQuery, WithHeadings, WithMapping
{


    use Exportable;

    protected $filters;
    protected $index; // <-- Contador global

    public function __construct(Request $request)
    {
        $this->filters = $request;
        $this->index = 1; // Inicializar el contador global
    }

    public function query()
    {
        $query = CeboDispatch::query();
        $query->with('supplier', 'products.product');

        if ($this->filters->has('id')) {
            $query->where('id', $this->filters->id);
        }

        if ($this->filters->has('suppliers')) {
            $query->whereIn('supplier_id', $this->filters->input('suppliers'));
        }

        if ($this->filters->has('dates')) {
            $dates = $this->filters->input('dates');
            $query->whereBetween('date', [$dates['start'], $dates['end']]);
        }

        if ($this->filters->has('species')) {
            $species = $this->filters->input('species');
            $query->whereHas('products.product', function ($query) use ($species) {
                $query->whereIn('species_id', $species);
            });
        }

        if ($this->filters->has('products')) {
            $products = $this->filters->input('products');
            $query->whereHas('products.product', function ($query) use ($products) {
                $query->whereIn('id', $products);
            });
        }

        if ($this->filters->has('notes')) {
            $query->where('notes', 'like', '%' . $this->filters->input('notes') . '%');
        }

        /* Order by Date Descen */
        $query->orderBy('date', 'desc');

        return $query;
    }

    public function map($ceboDispatch): array
    {
        $mappedProducts = [];

        if ($ceboDispatch->supplier->export_type == 'facilcom') {

            foreach ($ceboDispatch->products as $product) {
                $mappedProducts[] = [
                    'id' => $this->index,
                    /* Date format DD/MM/YYYY */
                    'date' => date('d/m/Y', strtotime($ceboDispatch->date)),
                    'supplierId' => $ceboDispatch->supplier->facilcom_cebo_code,
                    'supplierName' => $ceboDispatch->supplier->name,
                    /* 'date' => $ceboDispatch->date, */
                    'articleId' => $product->product->facil_com_code,
                    'articleName' => $product->product->article->name,
                    'netWeight' => $product->net_weight,
                    'price' => $product->price,
                    /* Lot es DDMMYYYY */
                    'lot' => date('dmY', strtotime($ceboDispatch->date)),
                ];
            }

            $this->index++;
        }

        return $mappedProducts;
    }

    public function headings(): array
    {
        return [
            'CODIGO',
            'Fecha',
            'CODIGO CLIENTE',
            'Destino',
            'Cod. Producto',
            'Producto',
            'Cantidad Kg',
            'Precio',
            'Lote asignado',
        ];
    }
}



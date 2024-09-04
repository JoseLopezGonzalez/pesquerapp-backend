<?php

namespace App\Exports\v1;

use App\Models\Box;
use App\Models\RawMaterialReception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class RawMaterialReceptionExport implements FromQuery, WithHeadings, WithMapping
{
    
    
    use Exportable;

    protected $filters;

    public function __construct(Request $request)
    {
        $this->filters = $request;
    }

    public function query()
    {
        $query = RawMaterialReception::query();
        $query->with('supplier', 'products.product.article');

        if ($this->filters->has('id')) {
            $query->where('id', $this->filters->input('id'));
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

        $query->orderBy('date', 'desc');

        return $query;
    }

    public function map($rawMaterialReception): array
    {
        $mappedProducts = [];

        foreach ($rawMaterialReception->products as $product) {
            $mappedProducts[] = [
                'id' => $rawMaterialReception->id,
                /* Date format DD/MM/YYYY */
                'date' => date('d/m/Y', strtotime($rawMaterialReception->date)),
                'supplierId' => $rawMaterialReception->supplier->id,
                /* 'date' => $rawMaterialReception->date, */
                'articleId' => $product->product->facil_com_code,
                'articleName' => $product->product->article->name,
                'netWeight' => $product->net_weight,
            ];
        }

        return $mappedProducts;
    }

    public function headings(): array
    {
        return [
            'CODIGO',
            'Fecha',
            'CODIGO CLIENTE',
            'Cod. Producto',
            'Producto',
            'Cantidad Kg',
        ];
    }
}



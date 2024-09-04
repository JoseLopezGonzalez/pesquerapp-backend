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
        $this->filters = $request;  // Almacenar la solicitud completa puede no ser lo ideal. Es mejor pasar solo los filtros necesarios.
    }

    public function query()
    {
        /* $query = Box::query();

        if ($this->filters->has('id')) {
            $id = $this->filters->input('id');
            $query->whereHas('palletBox', function ($subQuery) use ($id) {
                $subQuery->where('pallet_id', 'like', "%{$id}%");
            });
        }

        if ($this->filters->has('state')) {
            if ($this->filters->input('state') == 'stored') {
                $query->whereHas('palletBox.pallet', function ($subQuery) {
                    $subQuery->where('state_id', 2);
                });
            } else if ($this->filters->input('state') == 'shipped') {
                $query->whereHas('palletBox.pallet', function ($subQuery) {
                    $subQuery->where('state_id', 3);
                });
            }
        }

        if ($this->filters->has('position')) {
            if ($this->filters->input('position') == 'located') {
                $query->whereHas('palletBox.pallet', function ($subQuery) {
                    $subQuery->whereHas('storedPallet', function ($subSubQuery) {
                        $subSubQuery->whereNotNull('position');
                    });
                });
            } else if ($this->filters->input('position') == 'unlocated') {
                $query->whereHas('palletBox.pallet', function ($subQuery) {
                    $subQuery->whereHas('storedPallet', function ($subSubQuery) {
                        $subSubQuery->whereNull('position');
                    });
                });
            }
        }

        if ($this->filters->has('dates')) {
            $dates = $this->filters->input('dates');
            if (isset($dates['start'])) {
                $startDate = $dates['start'];
                $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
                $query->where('created_at', '>=', $startDate);
            }
            if (isset($dates['end'])) {
                $endDate = $dates['end'];
                $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
                $query->where('created_at', '<=', $endDate);
            }
        }

        if ($this->filters->has('notes')) {
            $notes = $this->filters->input('notes');
            $query->whereHas('palletBox.pallet', function ($subQuery) use ($notes) {
                $subQuery->where('observations', 'like', "%{$notes}%");
            });
        }

        if ($this->filters->has('lots')) {
            $lots = $this->filters->input('lots');
            $query->whereIn('lot', $lots);
        }

        if ($this->filters->has('products')) {
            $articles = $this->filters->input('products');
            $query->whereIn('article_id', $articles);
        } */

        $query = RawMaterialReception::query();
        $query->with('supplier', 'products.product');

        if ($this->filters->has('id')) {
            $query->where('id', $this->filters->input->id);
        }

        if ($this->filters->has('suppliers')) {
            $query->whereIn('supplier_id', $this->filters->input->suppliers);
        }

        if ($this->filters->has('dates')) {
            $query->whereBetween('date', [$this->filters->input->dates['start'], $this->filters->input->dates['end']]);
        }

        if ($this->filters->has('species')) {
            $species= $this->filters->input->species;
            $query->whereHas('products.product', function ($query) use ($species) {
                $query->whereIn('species_id', $this->filters->input->species);
            });
        }

        if ($this->filters->has('products')) {
            $products = $this->filters->input->products;
            $query->whereHas('products.product', function ($query) use ($products) {
                $query->whereIn('id', $this->filters->input->products);
            });
        }

        if ($this->filters->has('notes')) {
            $query->where('notes', 'like', '%' . $this->filters->input->notes . '%');
        }

        /* Order by Date Descen */
        $query->orderBy('date', 'desc');

        /* Hay que sacar Para cada linea (RawMaterialReception) de cada RawMaterialReception para ver los siguientes campos:
            - id : se saca de un indice en un bucle por cada RawMaterialReception (empezando por el 1)
            - date
            - articleId : products.product.article.id
            - articleName : products.product.article.name
            - netWeight : products.netWeight
        */

        $rawMaterialReceptionProducts = [];
        /* el id sale del índice en el primer foreach */
        foreach ($query as $index => $rawMaterialReception) {
            foreach ($rawMaterialReception->products as $product) {
                $rawMaterialReceptionProducts[] = [
                    'index' => $index, // Guardamos el índice del primer foreach
                    'id' => $rawMaterialReception->id,
                    'date' => $rawMaterialReception->date,
                    'articleId' => $product->product->article->id,
                    'articleName' => $product->product->article->name,
                    'netWeight' => $product->netWeight,
                ];
            }
        }

        return $rawMaterialReceptionProducts;

        /* return $query; */
    }
    
    public function headings(): array
    {
        return [
            'CODIGO', 
            'Fecha', 
            'Cod.  Producto', 
            'Producto', 
            'Cantidad Kg',
        ];
    }

    public function map($rawMaterialReceptionProducts): array
    {
        return [
            $rawMaterialReceptionProducts['id'],
            $rawMaterialReceptionProducts['date'],
            $rawMaterialReceptionProducts['articleId'],
            $rawMaterialReceptionProducts['articleName'],
            $rawMaterialReceptionProducts['netWeight'],

            
            /* $box->id,
            $box->article->article->name,  // Suponiendo que Box tiene una relación con Article y que article tiene un atributo 'name'
            $box->lot,
            $box->net_weight,
            $box->gross_weight,
            $box->created_at, */
            // Agrega aquí los atributos que desees exportar
        ];
    }
}

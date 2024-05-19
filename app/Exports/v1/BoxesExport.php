<?php

namespace App\Exports\v1;

use App\Models\Box;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class BoxesExport implements FromQuery
{
    use Exportable;

    protected $filters;

    public function __construct(Request $request)
    {
        $this->filters = $request;  // Almacenar la solicitud completa puede no ser lo ideal. Es mejor pasar solo los filtros necesarios.
    }

    public function query()
    {
        $query = Box::query();

        if ($this->filters->has('id')) {
            $id = $this->filters->input('id');
            /* en la tabla pallet_boxes , las cajas que cumplan que palet_id es 'like', "%{$id}%" */
            $query->whereHas('palletBox', function ($subQuery) use ($id) {
                $subQuery->where('pallet_id', 'like', "%{$id}%");
            });


            /* $query->where('id', 'like', "%{$text}%"); *//* 136.3-5.5 */
        }

        if($this->filters->has('state')){
            if($this->filters->input('state') == 'stored'){
                $query->where('state_id', 2);
            }else if($this->filters->input('state') == 'shipped'){
                $query->where('state_id', 3);
            }
        }

        /* Position */
        if ($this->filters->has('position')) {
            if($this->filters->input('position') == 'located'){
                $query->whereHas('storedPallet', function ($subQuery) {
                    $subQuery->whereNotNull('position');
                });
            }else if($this->filters->input('position') == 'unlocated'){
                $query->whereHas('storedPallet', function ($subQuery) {
                    $subQuery->whereNull('position');
                });
            }

           
        }

        /* Dates */

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
            $query->where('observations', 'like', "%{$notes}%");
        }

        if ($this->filters->has('lots')) {
            $lots = $this->filters->input('lots');
            $query->whereHas('boxes', function ($subQuery) use ($lots) {
                $subQuery->whereHas('box', function ($subSubQuery) use ($lots) {
                    $subSubQuery->whereIn('lot', $lots);
                });
            });
        }

        if ($this->filters->has('products')) {
            $articles = $this->filters->input('products');
            $query->whereHas('boxes', function ($subQuery) use ($articles) {
                $subQuery->whereHas('box', function ($subSubQuery) use ($articles) {
                    $subSubQuery->whereIn('article_id', $articles);
                });
            });
        }

        return $query;
    }
}
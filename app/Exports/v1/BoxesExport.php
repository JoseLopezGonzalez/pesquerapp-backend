<?php

namespace App\Exports\v1;

use App\Models\Box;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class BoxesExport implements FromQuery
{
    use Exportable;

    public function query()
    {
        return Box::query(); // Asegúrate de añadir los filtros necesarios aquí
    }
}
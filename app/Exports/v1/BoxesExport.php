<?php

namespace App\Exports\v1;

use App\Models\Box;
use Maatwebsite\Excel\Concerns\FromCollection;

class BoxesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Box::all();
    }
}

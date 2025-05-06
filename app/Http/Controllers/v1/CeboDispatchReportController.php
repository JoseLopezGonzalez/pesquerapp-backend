<?php

namespace App\Http\Controllers\v1;

use App\Exports\v1\BoxesExport;
use App\Exports\v1\CeboDispatchExport;
use App\Exports\v1\RawMaterialReceptionExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\BoxResource;
use App\Http\Resources\v1\PalletResource;
use App\Models\Box;
use App\Models\CeboDispatch;
use App\Models\Order;
use App\Models\OrderPallet;
use App\Models\Pallet;
use App\Models\PalletBox;
use App\Models\StoredPallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class CeboDispatchReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /*return response()->json(['message' => 'Hola Mundo'], 200);*/
    /* return PalletResource::collection(Pallet::all()); */

    /*  public function index()
    {
        return PalletResource::collection(Pallet::paginate(10));

    } */



    public function exportToExcel(Request $request)
    {
        try {
            // Aumentar el límite de memoria y tiempo de ejecución solo para esta operación
            ini_set('memory_limit', '1024M');
            ini_set('max_execution_time', 300);

            // Exportar en formato .xls (Excel 97-2003)
            return Excel::download(new CeboDispatchExport($request), 'cebo_dispatch_report.xls', \Maatwebsite\Excel\Excel::XLS);
        } catch (\Exception $e) {
            // Manejo de la excepción y retorno de un mensaje de error adecuado
            return response()->json(['error' => 'Error durante la exportación del archivo: ' . $e->getMessage()], 500);
        }
    }
}

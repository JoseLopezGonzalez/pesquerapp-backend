<?php

namespace App\Http\Controllers\v2;

use App\Exports\v2\OrderExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrdersReportController extends Controller
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
            return Excel::download(new OrderExport($request), 'orders_report.xls', \Maatwebsite\Excel\Excel::XLS);
        } catch (\Exception $e) {
            // Manejo de la excepción y retorno de un mensaje de error adecuado
            return response()->json(['error' => 'Error durante la exportación del archivo: ' . $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\v2;

use App\Exports\v2\OrderExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

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


    /* A3ERPOrderSalesDeliveryNoteExport */
    /*  public function exportToExcelA3ERP(Request $request)
     {
         try {
             // Aumentar el límite de memoria y tiempo de ejecución solo para esta operación
             ini_set('memory_limit', '1024M');
             ini_set('max_execution_time', 300);

             // Exportar en formato .xls (Excel 97-2003)
             return Excel::download(new OrderExport($request), 'orders_report_a3erp.xls', \Maatwebsite\Excel\Excel::XLS);
         } catch (\Exception $e) {
             // Manejo de la excepción y retorno de un mensaje de error adecuado
             return response()->json(['error' => 'Error durante la exportación del archivo: ' . $e->getMessage()], 500);
         }
     } */

    /* Diferente forma de hacer para xls de verdad, el otro 'Maatwebsite' no lo hace */
    public function exportToExcelA3ERP(Request $request)
    {
        try {
            ini_set('memory_limit', '1024M');
            ini_set('max_execution_time', 300);

            // Crear nuevo spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Aquí deberías colocar tu lógica real
            // Este es un ejemplo básico
            $sheet->setCellValue('A1', 'Producto');
            $sheet->setCellValue('B1', 'Cantidad');
            $sheet->setCellValue('A2', 'Pulpo T6');
            $sheet->setCellValue('B2', '120');

            // Establecer nombre del archivo
            $fileName = 'orders_report_a3erp.xls';

            // Preparar cabeceras
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=\"$fileName\"");
            header('Cache-Control: max-age=0');
            header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
            header('Pragma: public');

            // Crear el escritor real .xls
            $writer = new Xls($spreadsheet);
            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al generar XLS A3ERP: ' . $e->getMessage()], 500);
        }
    }





}

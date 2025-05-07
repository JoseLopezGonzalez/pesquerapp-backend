<?php

namespace App\Http\Controllers\v2;

use App\Exports\v2\A3ERPOrderSalesDeliveryNoteExport;
use App\Exports\v2\OrderBoxListExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\v2\OrdersExport;
use App\Exports\v2\ProductLotDetailsExport;
use App\Models\Order;

class ExcelController extends Controller
{
    /**
     * Generar exportación en función del tipo de archivo y entidad
     */
    private function generateExport($exportClass, $fileName)
    {
        return Excel::download(new $exportClass, "{$fileName}.xlsx");
    }

    public function exportOrders(Request $request)
    {
        ini_set('memory_limit', '1024M');
        return $this->generateExport(OrdersExport::class, 'orders_report');
    }


    public function exportProductLotDetails($orderId)
    {
        ini_set('memory_limit', '1024M');
        $order = Order::findOrFail($orderId);
        return Excel::download(new ProductLotDetailsExport($order), "product_lot_details_{$order->formattedId}.xlsx");
    }

    public function exportBoxList($orderId)
    {
        ini_set('memory_limit', '1024M');
        $order = Order::findOrFail($orderId);
        return Excel::download(new OrderBoxListExport($order), "box_list_{$order->formattedId}.xlsx");
    }


    public function exportA3ERPOrderSalesDeliveryNote($orderId)
    {
        ini_set('memory_limit', '1024M');
        $order = Order::findOrFail($orderId);
        return Excel::download(new A3ERPOrderSalesDeliveryNoteExport($order), "albaran_venta_{$order->formattedId}.xls");
    }


}

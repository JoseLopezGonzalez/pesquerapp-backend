<?php

// Dentro de app/Http/Controllers/v1/PDFController.php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Order; // Asegúrate de importar tu modelo Order
use Barryvdh\DomPDF\PDF;

class PDFController extends Controller
{
    /**
     * Generate a delivery note PDF for a specific order.
     *
     * @param int $orderId
     * @return \Illuminate\Http\Response
     */
    public function generateDeliveryNote($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $pdf = PDF::loadView('pdf.delivery_note', ['order' => $order]);
        return $pdf->download("delivery-note-{$order->id}.pdf");
    }
}


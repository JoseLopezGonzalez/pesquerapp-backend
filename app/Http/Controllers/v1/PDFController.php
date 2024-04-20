<?php

// Dentro de app/Http/Controllers/v1/PDFController.php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Order; // Asegúrate de importar tu modelo Order
//use PDF; // Comenta temporalmente esta línea para desactivar la generación de PDF

use Spatie\Browsershot\Browsershot; // Importa Browsershot
use function Spatie\LaravelPdf\Support\pdf;



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


        /* return pdf('pdf.invoice', [
            'order' => $order, 
        ]); */

        return view('pdf.delivery_note', ['order' => $order]);



        // Renderiza la vista como HTML
        //$html = view('pdf.delivery_note', ['order' => $order])->render();

        // Usa Browsershot para convertir el HTML a PDF
        /* $pdfContent = Browsershot::html($html)
            ->format('A4')
            ->showBackground()
            ->margins(10, 10, 10, 10)
            ->pdf(); */

        // Generar una respuesta de descarga con el PDF
       /*  return response()->streamDownload(function () use ($pdfContent) {
            echo $pdfContent;
        }, "delivery-note-{$order->id}.pdf", ['Content-Type' => 'application/pdf']); */

        /*  $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

         $pdf = PDF::loadView('pdf.delivery_note', ['order' => $order]);
         return $pdf->download("delivery-note-{$order->id}.pdf");
 */
        // Temporalmente, retornamos una vista en lugar de descargar un PDF
        /* return view('pdf.delivery_note', ['order' => $order]); */
    }
}

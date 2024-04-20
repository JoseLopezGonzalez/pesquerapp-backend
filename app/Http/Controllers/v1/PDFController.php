<?php

// Dentro de app/Http/Controllers/v1/PDFController.php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Order; // Asegúrate de importar tu modelo Order
use Illuminate\Support\Facades\Log;
//use PDF; // Comenta temporalmente esta línea para desactivar la generación de PDF

use Spatie\Browsershot\Browsershot; // Importa Browsershot
use Spatie\LaravelPdf\Facades\Pdf;
use Beganovich\Snappdf\Snappdf;




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


        $snappdf = new Snappdf();
        $html = view('pdf.invoice', ['data' => 'Your data here'])->render();
        $snappdf->setChromiumPath('/usr/bin/chromium-browser'); // Asegúrate de cambiar esto por tu ruta específica


        $snappdf->addChromiumArguments('--no-sandbox');

        // Agrega argumentos de Chromium uno por uno
        $snappdf->addChromiumArguments('disable-gpu');
        $snappdf->addChromiumArguments('disable-translate');
        $snappdf->addChromiumArguments('disable-extensions');
        $snappdf->addChromiumArguments('disable-sync');
        $snappdf->addChromiumArguments('disable-background-networking');
        $snappdf->addChromiumArguments('disable-software-rasterizer');
        $snappdf->addChromiumArguments('disable-default-apps');
        $snappdf->addChromiumArguments('disable-dev-shm-usage');
        $snappdf->addChromiumArguments('safebrowsing-disable-auto-update');
        $snappdf->addChromiumArguments('run-all-compositor-stages-before-draw');
        $snappdf->addChromiumArguments('no-first-run');
        $snappdf->addChromiumArguments('no-margins');
        $snappdf->addChromiumArguments('print-to-pdf-no-header');
        $snappdf->addChromiumArguments('no-pdf-header-footer');
        $snappdf->addChromiumArguments('hide-scrollbars');
        $snappdf->addChromiumArguments('ignore-certificate-errors');

        $pdf = $snappdf->setHtml($html)
            ->generate();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'invoice.pdf', ['Content-Type' => 'application/pdf']);


        /* try {
            return Pdf::view('pdf.invoice', ['order' => $order])
                ->format('a4')
                ->name('your-invoice.pdf');
        } catch (\Exception $e) {
            Log::error("Error generando PDF: " . $e->getMessage());
            return response()->json(['error' => 'Error al generar el PDF'], 500);
        } */

        /* return pdf('pdf.invoice', [
            'order' => $order, 
        ]); */

        /* return view('pdf.delivery_note', ['order' => $order]); */






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

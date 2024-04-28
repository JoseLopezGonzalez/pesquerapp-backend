<?php


namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Beganovich\Snappdf\Snappdf;

/* 
use Illuminate\Support\Facades\Log;
use PDF; 
use Spatie\Browsershot\Browsershot; 
use Spatie\LaravelPdf\Facades\Pdf; 
*/

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
        $html = view('pdf.delivery_note', ['order' => $order])->render();
        $snappdf->setChromiumPath('/usr/bin/google-chrome'); // Asegúrate de cambiar esto por tu ruta específica

        /* Personalizando el PDF */
        $snappdf->addChromiumArguments('--margin-top=10mm');
        $snappdf->addChromiumArguments('--margin-right=30mm');
        $snappdf->addChromiumArguments('--margin-bottom=10mm');
        $snappdf->addChromiumArguments('--margin-left=10mm');


        // Agrega argumentos de Chromium uno por uno
        // Configuración para que el servidor no de errores y pueda trabajar bien con el PDF
        $snappdf->addChromiumArguments('--no-sandbox');
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
        }, 'Delivery_note_' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function generateRestrictedDeliveryNote($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $snappdf = new Snappdf();
        $html = view('pdf.restricted_delivery_note', ['order' => $order])->render();
        $snappdf->setChromiumPath('/usr/bin/google-chrome'); // Asegúrate de cambiar esto por tu ruta específica

        /* Personalizando el PDF */
        $snappdf->addChromiumArguments('--margin-top=10mm');
        $snappdf->addChromiumArguments('--margin-right=30mm');
        $snappdf->addChromiumArguments('--margin-bottom=10mm');
        $snappdf->addChromiumArguments('--margin-left=10mm');


        // Agrega argumentos de Chromium uno por uno
        // Configuración para que el servidor no de errores y pueda trabajar bien con el PDF
        $snappdf->addChromiumArguments('--no-sandbox');
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
        }, 'Restricted_Delivery_note_' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function generateOrderSigns($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $snappdf = new Snappdf();
        $html = view('pdf.order_signs', ['order' => $order])->render();
        $snappdf->setChromiumPath('/usr/bin/google-chrome'); // Asegúrate de cambiar esto por tu ruta específica

        /* Personalizando el PDF */
        $snappdf->addChromiumArguments('--margin-top=10mm');
        $snappdf->addChromiumArguments('--margin-right=30mm');
        $snappdf->addChromiumArguments('--margin-bottom=10mm');
        $snappdf->addChromiumArguments('--margin-left=10mm');


        // Agrega argumentos de Chromium uno por uno
        // Configuración para que el servidor no de errores y pueda trabajar bien con el PDF
        $snappdf->addChromiumArguments('--no-sandbox');
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
        }, 'Order_sings_' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }
}

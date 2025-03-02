<?php


namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Beganovich\Snappdf\Snappdf;



class PDFController extends Controller
{
    /* v2 */

    /**
     * Método genérico para generar PDFs de cualquier entidad
     *
     * @param string $modelClass Modelo de la entidad (Ej: Order::class)
     * @param int $entityId ID de la entidad (Ej: orderId, invoiceId, etc.)
     * @param string $viewPath Ruta de la vista Blade (Ej: 'pdf.v2.orders.order_sheet')
     * @param string $fileName Nombre del archivo PDF (Ej: 'Hoja_de_pedido')
     * @param array $extraData Datos adicionales a pasar a la vista
     * @return \Illuminate\Http\Response
     */
    private function generatePdf($entity, $viewPath, $fileName, $extraData = [])
    {
        $snappdf = new Snappdf();
        $html = view($viewPath, array_merge(['entity' => $entity], $extraData))->render();
        $snappdf->setChromiumPath('/usr/bin/google-chrome');

        // Configuración de márgenes
        $snappdf->addChromiumArguments('--margin-top=10mm');
        $snappdf->addChromiumArguments('--margin-right=30mm');
        $snappdf->addChromiumArguments('--margin-bottom=10mm');
        $snappdf->addChromiumArguments('--margin-left=10mm');

        // Argumentos de optimización y compatibilidad
        $chromiumArgs = [
            '--no-sandbox',
            'disable-gpu',
            'disable-translate',
            'disable-extensions',
            'disable-sync',
            'disable-background-networking',
            'disable-software-rasterizer',
            'disable-default-apps',
            'disable-dev-shm-usage',
            'safebrowsing-disable-auto-update',
            'run-all-compositor-stages-before-draw',
            'no-first-run',
            'no-margins',
            'print-to-pdf-no-header',
            'no-pdf-header-footer',
            'hide-scrollbars',
            'ignore-certificate-errors'
        ];

        foreach ($chromiumArgs as $arg) {
            $snappdf->addChromiumArguments($arg);
        }

        // Generar PDF
        $pdf = $snappdf->setHtml($html)->generate();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, "{$fileName}.pdf", ['Content-Type' => 'application/pdf']);
    }

    /* Métodos específicos para cada tipo de documento */

    public function generateOrderSheet($orderId)
    {
        $order = Order::findOrFail($orderId);
        $fileName = 'Hoja_de_pedido_' . $order->formattedId;
        return $this->generatePdf($order, 'pdf.v2.orders.order_sheet', $fileName);
    }

    public function generateOrderSigns($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $snappdf = new Snappdf();
        $html = view('pdf.v2.orders.order_signs', ['order' => $order])->render();
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
        }, 'Letreros_transporte_' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function generateOrderPackingList($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $snappdf = new Snappdf();
        $html = view('pdf.v2.orders.order_packing_list', ['order' => $order])->render();
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
        }, 'Packing_list_' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function generateLoadingNote($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $snappdf = new Snappdf();
        $html = view('pdf.v2.orders.loading_note', ['order' => $order])->render();
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
        }, 'Nota_de_carga_' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function generateRestrictedLoadingNote($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $snappdf = new Snappdf();
        $html = view('pdf.v2.orders.restricted_loading_note', ['order' => $order])->render();
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
        }, 'Nota_de_carga_restringida_' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function generateOrderCMR($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $snappdf = new Snappdf();
        $html = view('pdf.v2.orders.CMR', ['order' => $order])->render();
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
        }, 'CMR_' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }
}

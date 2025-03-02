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
        $order = Order::findOrFail($orderId);
        $fileName = 'Letreros_transporte_' . $order->formattedId;
        return $this->generatePdf($order, 'pdf.v2.orders.order_signs', $fileName);
    }

    public function generateOrderPackingList($orderId)
    {
        $order = Order::findOrFail($orderId);
        $fileName = 'Packing_list_' . $order->formattedId;
        return $this->generatePdf($order, 'pdf.v2.orders.order_packing_list', $fileName);
    }

    public function generateLoadingNote($orderId)
    {
        $order = Order::findOrFail($orderId);
        $fileName = 'Nota_de_carga_' . $order->formattedId;
        return $this->generatePdf($order, 'pdf.v2.orders.loading_note', $fileName);
    }

    public function generateRestrictedLoadingNote($orderId)
    {
        $order = Order::findOrFail($orderId);
        $fileName = 'Nota_de_carga_restringida_' . $order->formattedId;
        return $this->generatePdf($order, 'pdf.v2.orders.restricted_loading_note', $fileName);
    }

    public function generateOrderCMR($orderId)
    {
        $order = Order::findOrFail($orderId);
        $fileName = 'CMR_' . $order->formattedId;
        return $this->generatePdf($order, 'pdf.v2.orders.CMR', $fileName);
    }

    public function generateDeliveryNote($orderId)
    {
        $order = Order::findOrFail($orderId);
        $fileName = 'Nota_de_entrega_' . $order->formattedId;
        return $this->generatePdf($order, 'pdf.v2.orders.delivery_note', $fileName);
    }

    public function generateInvoice($orderId)
    {
        $order = Order::findOrFail($orderId);
        $fileName = 'Factura_' . $order->formattedId;
        return $this->generatePdf($order, 'pdf.v2.orders.invoice', $fileName);
    }
}

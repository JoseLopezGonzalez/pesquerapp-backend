<?php

namespace App\Services;

use App\Models\Order;
use Beganovich\Snappdf\Snappdf;

class PDFService
{
    /**
     * Generar un documento PDF para un pedido y devolver su ruta.
     */
    public function generateDocument(Order $order, string $docType): string
    {
        $formattedId = str_replace('#', '', $order->formattedId);
        $pdfPath = storage_path("app/public/{$docType}-{$formattedId}.pdf");

        // Si ya existe, no lo volvemos a crear (opcional, puedes quitar esto si prefieres siempre regenerar)
        if (file_exists($pdfPath)) {
            return $pdfPath;
        }

        $viewsMap = [
            'nota_carga' => 'pdf.v2.orders.loading_note',
            'packing_list' => 'pdf.v2.orders.order_packing_list',
            'cmr' => 'pdf.v2.orders.CMR',
        ];

        $viewName = $viewsMap[$docType] ?? null;

        if (!$viewName) {
            throw new \Exception("No existe la vista para el tipo de documento: {$docType}");
        }

        $html = view($viewName, ['order' => $order])->render();

        // Crear el PDF
        $snappdf = new Snappdf();
        $snappdf->setChromiumPath('/usr/bin/google-chrome');

        // Opciones de Snappdf (las que ya usas)
        $snappdf->addChromiumArguments('--no-sandbox');
        $snappdf->addChromiumArguments('--margin-top=10mm');
        $snappdf->addChromiumArguments('--margin-right=30mm');
        $snappdf->addChromiumArguments('--margin-bottom=10mm');
        $snappdf->addChromiumArguments('--margin-left=10mm');
        $snappdf->addChromiumArguments('--disable-gpu');
        $snappdf->addChromiumArguments('--disable-translate');
        $snappdf->addChromiumArguments('--disable-extensions');
        $snappdf->addChromiumArguments('--disable-sync');
        $snappdf->addChromiumArguments('--disable-background-networking');
        $snappdf->addChromiumArguments('--disable-software-rasterizer');
        $snappdf->addChromiumArguments('--disable-default-apps');
        $snappdf->addChromiumArguments('--disable-dev-shm-usage');
        $snappdf->addChromiumArguments('--safebrowsing-disable-auto-update');
        $snappdf->addChromiumArguments('--run-all-compositor-stages-before-draw');
        $snappdf->addChromiumArguments('--no-first-run');
        $snappdf->addChromiumArguments('--print-to-pdf-no-header');
        $snappdf->addChromiumArguments('--no-pdf-header-footer');
        $snappdf->addChromiumArguments('--hide-scrollbars');
        $snappdf->addChromiumArguments('--ignore-certificate-errors');

        // Generar y guardar el PDF
        $pdfContent = $snappdf->setHtml($html)->generate();
        file_put_contents($pdfPath, $pdfContent);

        return $pdfPath;
    }
}

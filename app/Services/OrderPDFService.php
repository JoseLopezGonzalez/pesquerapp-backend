<?php

namespace App\Services;

use App\Models\Order;
use Beganovich\Snappdf\Snappdf;


/**
 * Service especializado en generación de PDFs relacionados con Orders.
 * 
 * NOTA: Si se requiere para otras entidades, considerar crear un PDFService general.
 */


class OrderPDFService
{
    /**
     * Generar un documento PDF y devolver su ruta.
     */
    public function generateDocument(Order $order, string $docType): string
    {
        $formattedId = str_replace('#', '', $order->formattedId);
        $pdfPath = storage_path("app/public/{$docType}-{$formattedId}.pdf");

        // Si ya existe no lo regeneramos (opcional)
        if (file_exists($pdfPath)) {
            return $pdfPath;
        }

        // ✅ Mapear tipo de documento a vista real
        $viewsMap = [
            'nota_carga' => 'pdf.v2.orders.loading_note',
            'packing_list' => 'pdf.v2.orders.order_packing_list',
            'cmr' => 'pdf.v2.orders.CMR',
        ];

        $viewName = $viewsMap[$docType] ?? null;

        if (!$viewName) {
            throw new \Exception("No existe la vista para el tipo de documento: {$docType}");
        }

        // ⚠️ Pasar la variable como 'entity', no como 'order'
        $html = view($viewName, ['entity' => $order])->render();

        // Crear PDF con Snappdf
        $snappdf = new Snappdf();
        $snappdf->setChromiumPath('/usr/bin/google-chrome');

        // Opciones Chromium
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

        // Generar contenido
        $pdfContent = $snappdf->setHtml($html)->generate();

        // Guardar el archivo
        file_put_contents($pdfPath, $pdfContent);

        return $pdfPath;
    }
}

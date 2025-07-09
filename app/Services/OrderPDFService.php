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
     *
     * @param Order $order
     * @param string $docType
     * @param string $viewName
     * @return string
     */
    public function generateDocument(Order $order, string $docType, string $viewPath): string
    {
        $formattedId = str_replace('#', '', $order->formattedId);
        $pdfPath = storage_path("app/public/{$docType}-{$formattedId}.pdf");

        /* // Si ya existe no lo regeneramos (opcional) */
        // ⏱️ Si existe, verificar si ha sido generado hace menos de 30 segundos
        if (file_exists($pdfPath)) {
            $lastModified = filemtime($pdfPath);
            $now = time();
            $ageInSeconds = $now - $lastModified;

            if ($ageInSeconds < 30) {
                return $pdfPath; // ✅ Reutilizar si es reciente
            }

            // 🗑️ Eliminar si está obsoleto
            unlink($pdfPath);
        }


        // ⚠️ Pasar la variable como 'entity', no como 'order'
        $html = view($viewPath, ['entity' => $order])->render();

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

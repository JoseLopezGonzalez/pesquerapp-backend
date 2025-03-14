<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderMailerService
{
    /**
     * Envío personalizado de documentos.
     */
    public function sendDocuments(Order $order, array $documents): void
    {
        foreach ($documents as $document) {
            $this->sendDocument($order, $document['type'], $document['recipients']);
        }
    }

    /**
     * Envío estándar de documentos (según config).
     */
    public function sendStandardDocuments(Order $order): void
    {
        $recipientsConfig = config('order_documents.standard_recipients');

        foreach ($recipientsConfig as $recipientKey => $docTypes) {

            // ✅ Obtenemos email desde las entidades dinámicamente
            [$mainEmails, $ccEmails] = $this->getEmailsFromEntities($order, $recipientKey);
            if (empty($mainEmails)) continue; // Saltamos si no hay email

            $subject = "Documentación del Pedido #{$order->formattedId}";

            $documentsToAttach = [];
            foreach ($docTypes as $docType) {
                // Quitar "#" del formattedId para el nombre del archivo
                $formattedId = str_replace('#', '', $order->formattedId);
                $pdfPath = storage_path("app/public/{$docType}-{$formattedId}.pdf");

                // ✅ Verificar que el archivo exista
                if (!file_exists($pdfPath)) {
                    \Log::error("No se encuentra el documento: {$pdfPath}");
                    continue; // Saltar este documento si no existe
                }

                $documentName = ucfirst(str_replace('_', ' ', $docType)) . " - Pedido {$order->formattedId}.pdf";

                $documentsToAttach[] = [
                    'path' => $pdfPath,
                    'name' => $documentName
                ];
            }

            // Saltamos si no hay documentos
            if (empty($documentsToAttach)) continue;

            // Definir Markdown según destinatario
            $markdownTemplates = [
                'cliente' => 'emails.orders.shipped',
                'transporte' => 'emails.orders.transport_details',
                'comercial' => 'emails.orders.commercial',
            ];
            $markdownTemplate = $markdownTemplates[$recipientKey] ?? 'emails.orders.shipped';

            // Crear y enviar
            $mailable = new \App\Mail\StandardOrderDocuments(
                $order,
                $subject,
                $markdownTemplate,
                $documentsToAttach
            );

            Mail::to((array) $mainEmails)
                ->cc((array) $ccEmails)
                ->send($mailable);
        }
    }

    /**
     * Lógica interna para envío personalizado.
     */
    private function sendDocument(Order $order, string $docType, array $recipients): void
    {
        $config = config('order_documents');

        $documentConfig = $config['documents'][$docType] ?? null;
        if (!$documentConfig) return;

        $subject = str_replace('{order_id}', $order->formattedId, $documentConfig['subject_template']);
        $bodyTemplate = $documentConfig['body_template'];
        $documentName = ucfirst(str_replace('_', ' ', $docType));

        foreach ($recipients as $recipientKey) {
            [$mainEmails, $ccEmails] = $this->getEmailsFromEntities($order, $recipientKey);
            if (empty($mainEmails)) continue;

            $mailable = new \App\Mail\GenericOrderDocument($order, $bodyTemplate, $subject, $documentName);

            /* DCetener ejecucion y mostrar emails */
            dd($mainEmails, $ccEmails);
            Mail::to((array) $mainEmails)
                ->cc((array) $ccEmails)
                ->send($mailable);
        }
    }

    /**
     * Obtener emails dinámicos desde las entidades.
     */
    private function getEmailsFromEntities(Order $order, string $recipientKey): array
    {
        $mainEmails = [];
        $ccEmails = [];

        switch ($recipientKey) {
            case 'cliente':
                $mainEmails = $order->emailsArray ?? [];
                $ccEmails = $order->ccEmailsArray ?? [];
                break;

            case 'transporte':
                $mainEmails = $order->transport->emailsArray ?? [];
                $ccEmails = $order->transport->ccEmailsArray ?? [];
                break;

            case 'comercial':
                if ($order->salesperson) {
                    $mainEmails = $order->salesperson->emailsArray ?? [];
                    $ccEmails = $order->salesperson->ccEmailsArray ?? [];
                } elseif ($order->comercial) {
                    $mainEmails = $order->comercial->emailsArray ?? [];
                    $ccEmails = $order->comercial->ccEmailsArray ?? [];
                }
                break;
        }

        return [$mainEmails, $ccEmails];
    }
}

<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

class AzureDocumentAIController extends Controller
{
    public function processPdf(Request $request)
    {
        // 1. Validar PDF
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        // 2. Guardar temporalmente el archivo PDF
        $path = $request->file('pdf')->store('pdfs');
        $fullPath = Storage::path($path);

        // 3. Leer contenido PDF
        $pdfContent = file_get_contents($fullPath);

        // 4. Obtener configuración desde .env
        $endpoint = env('AZURE_DOCUMENT_AI_ENDPOINT');
        $apiKey = env('AZURE_DOCUMENT_AI_KEY');
        $apiVersion = '2024-02-29-preview'; // Última versión API disponible

        // 5. Construir la URL para llamar a Azure Form Recognizer
        $url = "{$endpoint}formrecognizer/documentModels/prebuilt-document:analyze?api-version={$apiVersion}";

        // 6. Cliente HTTP Guzzle
        $client = new Client();

        try {
            // 7. Llamada HTTP a Azure Form Recognizer
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => $apiKey,
                    'Content-Type' => 'application/pdf',
                ],
                'body' => $pdfContent,
            ]);

            // 8. Azure devuelve la URL del resultado en el header "Operation-Location"
            $operationUrl = $response->getHeader('Operation-Location')[0];

            // 9. Esperar a que Azure termine de analizar (Polling cada pocos segundos)
            do {
                sleep(2); // Espera 2 segundos antes de volver a comprobar

                $resultResponse = $client->request('GET', $operationUrl, [
                    'headers' => [
                        'Ocp-Apim-Subscription-Key' => $apiKey,
                    ],
                ]);

                $result = json_decode($resultResponse->getBody(), true);

                $status = $result['status'];

            } while ($status === 'running' || $status === 'notStarted');

            if ($status !== 'succeeded') {
                return response()->json(['error' => 'Error en análisis del documento'], 500);
            }

            // 10. Retornar resultado
            return response()->json([
                'message' => 'Procesado con éxito',
                'analysis' => $result['analyzeResult'],
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

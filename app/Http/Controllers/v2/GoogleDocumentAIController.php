<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Google\Cloud\DocumentAI\V1\DocumentProcessorServiceClient;
use Google\Cloud\DocumentAI\V1\RawDocument;
use Google\Cloud\DocumentAI\V1\ProcessRequest;

class GoogleDocumentAIController extends Controller
{
    public function processPdf(Request $request)
    {
        // Validar PDF
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        // Guardar temporal
        $path = $request->file('pdf')->store('pdfs');
        $fullPath = Storage::path($path);

        // Credenciales
        $credentialsPath = storage_path('app/google-credentials.json'); // Ajusta a tu archivo
        $projectId = '223147234811';
        $location = 'eu'; // ej. "eu" o "us"
        $processorId = '8ac94b1c45e776ee'; // tu ID, p. ej: 1091d309f8ae

        // Crear el cliente
        $documentProcessor = new DocumentProcessorServiceClient([
            'credentials' => $credentialsPath
        ]);

        // Construir el nombre del processor
        $name = $documentProcessor->processorName($projectId, $location, $processorId);

        // Leer el PDF
        $content = file_get_contents($fullPath);
        $rawDocument = (new RawDocument())
            ->setContent($content)
            ->setMimeType('application/pdf');

        // Armar la request
        $requestProcess = (new ProcessRequest())
            ->setName($name)
            ->setRawDocument($rawDocument);

        // Llamar a Document AI
        try {
            $response = $documentProcessor->processDocument($requestProcess);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        $document = $response->getDocument();

        // Ejemplo: extraer texto completo y/o Entities
        $fullText = $document->getText();
        // Si es un Custom Processor con campos etiquetados,
        // podrías explorar $document->getEntities() para ver tus campos.

        return response()->json([
            'message' => 'Procesado con éxito',
            'fullText' => $fullText,
            // 'entities' => $document->getEntities(), // si quieres debug
        ]);
    }
}

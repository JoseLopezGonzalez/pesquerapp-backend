<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Google\Cloud\DocumentAI\V1\Client\DocumentProcessorServiceClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        // Credenciales y configuración
        $credentialsPath = storage_path('app/google-credentials.json');
        $projectId   = '223147234811';
        $location    = 'eu';
        $processorId = '8ac94b1c45e776ee';

        // Crear cliente Document AI
        $documentProcessor = new DocumentProcessorServiceClient([
            'credentials' => $credentialsPath,
            'apiEndpoint' => 'eu-documentai.googleapis.com',
        ]);

        // Nombre del procesador (o versión específica si quieres forzar una)
        $name = $documentProcessor->processorName($projectId, $location, $processorId);

        // Leer el PDF
        $content = file_get_contents($fullPath);
        $rawDocument = (new RawDocument())
            ->setContent($content)
            ->setMimeType('application/pdf');

        // Construir la request
        $requestProcess = (new ProcessRequest())
            ->setName($name)
            ->setRawDocument($rawDocument);

        // Llamar a Document AI
        try {
            $response = $documentProcessor->processDocument($requestProcess);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // Obtener el Document
        $document = $response->getDocument();

        // Texto completo (opcional)
        $fullText = $document->getText();

        // Entities (etiquetas) personalizadas
        $entitiesList = $document->getEntities();
        $entities = [];

        foreach ($entitiesList as $entity) {
            $entities[] = [
                'type'       => $entity->getType(),
                'value'      => $entity->getMentionText(),
                'confidence' => $entity->getConfidence(),
            ];
        }

        // ***** Aquí recuperamos la versión usada *****
        $versionUsed = $response->getProcessorVersion(); // Devuelve la ruta con la versión

        // Respuesta JSON
        return response()->json([
            'message'     => 'Procesado con éxito',
            'versionUsed' => $versionUsed,   // Aquí ves qué versión se usó
            'fullText'    => $fullText,
            'entities'    => $entities,
        ]);
    }
}

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
        // 1. Validar PDF
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        // 2. Guardar temporal
        $path = $request->file('pdf')->store('pdfs');
        $fullPath = Storage::path($path);

        // 3. Configurar credenciales y datos de Document AI
        $credentialsPath = storage_path('app/google-credentials.json'); 
        $projectId   = '223147234811';
        $location    = 'eu'; 
        $processorId = '3c49f1160f79a1af'; 

        // 4. Crear el cliente DocumentProcessorService
        $documentProcessor = new DocumentProcessorServiceClient([
            'credentials' => $credentialsPath,
            'apiEndpoint' => 'eu-documentai.googleapis.com',
        ]);

        // 5. Construir el nombre del procesador
        $name = $documentProcessor->processorName($projectId, $location, $processorId);

        // 6. Leer el PDF como RawDocument
        $content = file_get_contents($fullPath);
        $rawDocument = (new RawDocument())
            ->setContent($content)
            ->setMimeType('application/pdf');

        // 7. Preparar la solicitud
        $requestProcess = (new ProcessRequest())
            ->setName($name)
            ->setRawDocument($rawDocument);

        // 8. Llamar a Document AI
        try {
            $response = $documentProcessor->processDocument($requestProcess);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // 9. Obtener el Document resultante
        $document = $response->getDocument();

        // 10. (Opcional) Texto completo (si quieres verlo)
        $fullText = $document->getText();

        // 11. Iterar sobre las entidades (etiquetas personalizadas)
        $entitiesList = $document->getEntities();
        $entities = [];  // Aquí guardamos tus campos etiquetados

        foreach ($entitiesList as $entity) {
            $entities[] = [
                'type'       => $entity->getType(),         // nombre de la etiqueta
                'value'      => $entity->getMentionText(),  // texto detectado
                'confidence' => $entity->getConfidence(),   // nivel de confianza
            ];
        }

        // 12. Devolver en JSON el texto y las entidades
        return response()->json([
            'message'  => 'Procesado con éxito',
            'fullText' => $fullText,   // O quítalo si no quieres mostrar el OCR completo
            'entities' => $entities,   // Etiquetas con valores
        ]);
    }
}

<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class PdfExtractionController extends Controller
{
    public function extract(Request $request)
    {
        // Validar que venga un archivo 'pdf' en la petición
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:20480', // Máx 20 MB, por ejemplo
        ]);

        // Guardar el archivo temporalmente en: storage/app/pdfs/xxxx.pdf
        $path = $request->file('pdf')->store('pdfs');
        $fullPath = Storage::path($path);

        // Instanciar parser y parsear el PDF
        $parser = new Parser();
        $pdf    = $parser->parseFile($fullPath);

        // Extraer todo el texto
        $extractedText = $pdf->getText();

        // Procesar el texto para generar un JSON
        $jsonData = $this->processPdfText($extractedText);

        // Retornar JSON
        return response()->json([
            'message' => 'PDF procesado correctamente',
            'data'    => $jsonData,
        ]);
    }

    private function processPdfText(string $text): array
    {
        // Por ejemplo, separar en líneas
        $lines = collect(explode("\n", $text))
            ->map(fn($line) => trim($line))
            ->filter()
            ->values();

        // Tu lógica de parseo
        // (Busca "Comprador:", "Fecha:", "PULPO", etc.)
        $jsonData = [
            'raw_text' => $text,
            'line_count' => $lines->count(),
            // ... otros campos que necesites
        ];

        return $jsonData;
    }
}

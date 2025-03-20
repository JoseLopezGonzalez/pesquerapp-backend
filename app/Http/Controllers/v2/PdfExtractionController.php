<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class PdfExtractionController extends Controller
{
    public function extract(Request $request)
    {
        // 1. Validar que el archivo sea un PDF
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:20480', // Máx 20 MB, ajusta a tu gusto
        ]);

        // 2. Guardar el archivo en storage/app/pdfs
        $path = $request->file('pdf')->store('pdfs');
        $fullPath = Storage::path($path);

        // 3. Instanciar el parser y procesar
        $parser = new Parser();
        $pdf    = $parser->parseFile($fullPath);

        // 4. Extraer texto
        $text = $pdf->getText();

        // 5. Convertir texto en estructura JSON con lógica personalizada
        $jsonData = $this->processPdfText($text);

        return response()->json([
            'message' => 'PDF procesado correctamente',
            'data'    => $jsonData,
        ]);
    }

    /**
     * Parseo específico para la lonja de Punta Umbría
     * Ajusta la lógica según tus necesidades.
     */
    private function processPdfText(string $text): array
    {
        // Separar en líneas y limpiar vacías
        $lines = collect(explode("\n", $text))
            ->map(fn($line) => trim($line))
            ->filter()
            ->values();

        // Estructura base
        $jsonData = [
            'buyer' => '',
            'company' => '',
            'date' => '',
            'purchases' => [],
            'services' => [],
            'totals' => [],
        ];

        // Recorrer cada línea para encontrar patrones
        for ($i = 0; $i < $lines->count(); $i++) {
            $line = $lines[$i];

            // 1. Detectar "Comprador:###"
            if (preg_match('/^Comprador:(\S+)/', $line, $m)) {
                $jsonData['buyer'] = $m[1] ?? '';
                // Siguiente línea se asume nombre de la empresa
                $jsonData['company'] = $lines->get($i + 1, '');
            }

            // 2. Detectar "Fecha:DD/MM/YYYY"
            if (preg_match('/^Fecha:(.+)/', $line, $m)) {
                $jsonData['date'] = trim($m[1]);
            }

            // 3. Detectar líneas de producto (PULPO, etc.)
            //    Ejemplo de línea: "1  M19,40 PULPO ABUELO PURGA816 4,00 77,60 GARCIA RAMOS, ANTONIO JOSE"
            if ($this->isPurchaseLine($line)) {
                // Usamos un regex para capturar las partes
                // boxes, weight, product, pricePerKg, total, seller
                // Ajusta según tus PDFs reales
                $pattern = '/^(?<boxes>\d+)\s+M(?<weight>[\d,\.]+)\s+(?<rest>.+?)\s+(?<price>[\d,\.]+)\s+(?<total>[\d,\.]+)\s+(?<seller>.+)$/';
                if (preg_match($pattern, $line, $matches)) {
                    $jsonData['purchases'][] = [
                        'boxes' => $matches['boxes'] ?? '',
                        'weight' => $matches['weight'] ?? '',
                        'product' => $this->parseProduct($matches['rest']),
                        'pricePerKg' => $matches['price'] ?? '',
                        'total' => $matches['total'] ?? '',
                        'seller' => trim($matches['seller'] ?? ''),
                    ];
                }
            }

            // 4. Detectar servicios (TARIFA, CUOTA, etc.)
            //    Ejemplo de línea: "29  G TARIFA EXPLOTACION LONJA 06/03/2025 1.278,00 1,00 12,78 21,00"
            if ($this->isServiceLine($line)) {
                // Simplificado: partimos la línea por espacios y tomamos lo esencial
                $parts = preg_split('/\s+/', $line);
                // Por ejemplo, la penúltima parte es el precio, etc.
                $jsonData['services'][] = [
                    'description' => $line,
                ];
            }

            // 5. Totales
            //    Al ver "Total Pesca", tomamos la siguiente línea como el importe
            if (str_contains($line, 'Total Pesca')) {
                $jsonData['totals']['totalFishing'] = $lines->get($i + 1, '');
            }
            if (str_contains($line, 'IVA  Pesca')) {
                $jsonData['totals']['ivaFishing'] = $lines->get($i + 1, '');
            }
            if ($line === 'Total') {
                // A veces la línea "Total" solita indica que la siguiente es el total general
                $jsonData['totals']['grandTotal'] = $lines->get($i + 1, '');
            }
        }

        return $jsonData;
    }

    /**
     * Determina si la línea parece una línea de compra.
     * Puede cambiar según tus PDFs (en este ejemplo, miramos si contiene "PULPO", "CALAMAR", etc.)
     */
    private function isPurchaseLine(string $line): bool
    {
        // Búscamos "PULPO" o "MERLUZA" u otras palabras clave
        // O bien revisamos si empieza con "<numero>  M"
        if (preg_match('/^\d+\s+M[\d,\.]+/', $line) && str_contains($line, 'PULPO')) {
            return true;
        }
        // Añade más condiciones si fuera necesario
        return false;
    }

    /**
     * Determina si la línea parece de servicios.
     * (En el PDF real aparecen "TARIFA", "CUOTA", "SERV.")
     */
    private function isServiceLine(string $line): bool
    {
        // Simplificado: si la línea contiene "TARIFA", "CUOTA", etc.
        return preg_match('/(TARIFA|CUOTA|SERV\.)/i', $line);
    }

    /**
     * Si la parte de producto contiene "PULPO ABUELO PURGA816", etc.,
     * podemos aislarlo mejor si fuera necesario.
     */
    private function parseProduct(string $rest): string
    {
        // Aquí podrías separar "PULPO", "ABUELO", "PURGA816"...
        // De momento, devolvemos tal cual
        return trim($rest);
    }
}

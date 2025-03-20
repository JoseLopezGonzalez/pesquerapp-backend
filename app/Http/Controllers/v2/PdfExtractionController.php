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
        // 1. Validar que venga un archivo 'pdf'
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        // 2. Guardar temporal
        $path = $request->file('pdf')->store('pdfs');
        $fullPath = Storage::path($path);

        // 3. Parsear con smalot
        $parser = new Parser();
        $pdf = $parser->parseFile($fullPath);
        $text = $pdf->getText();

        // 4. Procesar texto para convertirlo en JSON estructurado
        $jsonData = $this->processPdfText($text);

        return response()->json([
            'message' => 'PDF procesado correctamente',
            'data'    => $jsonData,
        ]);
    }

    private function processPdfText(string $text): array
    {
        // 1) Reemplaza tabulaciones por espacio, unifica dobles espacios, etc.
        $text = str_replace("\t", " ", $text);
        // Opcional: quita retornos de carro Windows (\r)
        $text = str_replace("\r", "", $text);

        // 2) Separa en líneas por "\n", limpia espacios en los bordes
        $lines = explode("\n", $text);

        // 3) Pasada previa: a veces una línea que corresponde a la compra
        //    está partida en 2. Podemos intentar “pegar” la siguiente línea
        //    si no cumple un patrón y la siguiente sí. (Heurístico)
        $cleanedLines = [];
        $i = 0;
        while ($i < count($lines)) {
            $line = trim($lines[$i] ?? '');

            // Si la línea no “parece” terminada y la siguiente línea no inicia con un dígito,
            // quizá es una continuación. Ejemplo, "BELLITA CALE834" en la siguiente línea.
            if (
                isset($lines[$i+1]) &&
                !$this->startsWithDigit(trim($lines[$i+1])) && // la siguiente no empieza con dígito
                !$this->isLikelyNewSection($lines[$i+1])       // y no es una sección nueva
            ) {
                // Unimos con un espacio
                $line .= ' ' . trim($lines[$i+1]);
                $i += 2; // saltamos la siguiente
            } else {
                $i++;
            }

            // Reemplaza múltiples espacios contiguos por uno solo
            $line = preg_replace('/\s{2,}/', ' ', $line);
            if ($line !== '') {
                $cleanedLines[] = $line;
            }
        }

        // 4) Construir el JSON final
        $jsonData = [
            'buyer'     => '',
            'company'   => '',
            'date'      => '',
            'purchases' => [],
            'services'  => [],
            'totals'    => [],
        ];

        // Recorremos las líneas limpias y tratamos de matchear
        for ($i = 0; $i < count($cleanedLines); $i++) {
            $line = $cleanedLines[$i];

            // A) Comprador
            if (preg_match('/^Comprador:(\S+)/', $line, $m)) {
                $jsonData['buyer'] = $m[1];
                // Siguiente línea es la empresa
                $jsonData['company'] = $cleanedLines[$i+1] ?? '';
            }

            // B) Fecha (buscamos "Fecha:DD/MM/YYYY")
            if (preg_match('/^Fecha:(.+)/', $line, $m)) {
                $jsonData['date'] = trim($m[1]);
            }

            // C) Detectar si es línea de compra
            //    Ej: "1 M19,40 PULPO ABUELO PURGA816 4,00 77,60 GARCIA RAMOS, ANTONIO JOSE"
            //    Regex robusto con 6 grupos:
            //    boxes, "M{kilos}", product, price, total, seller
            if ($this->isPurchaseLine($line)) {
                // Ojo: a veces "M" va pegado a la cifra ("M19,40"), otras no.
                // Metemos la "M" en la detección
                $pattern = '/^(?<boxes>\d+)\s+M(?<weight>[\d,\.]+,\d+)\s+(?<product>.+?)\s+(?<price>[\d,\.]+,\d+)\s+(?<total>[\d,\.]+,\d+)\s+(?<seller>.+)$/';

                if (preg_match($pattern, $line, $matches)) {
                    $jsonData['purchases'][] = [
                        'boxes'     => $matches['boxes'],
                        'weight'    => $matches['weight'],
                        'product'   => $this->parseProduct($matches['product']),
                        'pricePerKg'=> $matches['price'],
                        'total'     => $matches['total'],
                        'seller'    => trim($matches['seller']),
                    ];
                }
            }

            // D) Detectar servicios (líneas con "TARIFA", "CUOTA", etc.)
            if (preg_match('/(TARIFA|CUOTA|SERV\.)/i', $line)) {
                $jsonData['services'][] = [
                    'description' => $line,
                ];
            }

            // E) Totales
            if (str_contains($line, 'Total Pesca')) {
                $jsonData['totals']['totalFishing'] = $cleanedLines[$i+1] ?? '';
            }
            if (str_contains($line, 'IVA  Pesca')) {
                $jsonData['totals']['ivaFishing'] = $cleanedLines[$i+1] ?? '';
            }
            // A veces "Total" solito
            if ($line === 'Total') {
                $jsonData['totals']['grandTotal'] = $cleanedLines[$i+1] ?? '';
            }
        }

        return $jsonData;
    }

    /**
     * Indica si la línea parece una línea de compra.
     * Búsqueda simple: empieza con un dígito + " M" + algo + "PULPO" (u otra palabra),
     * o ves "BELLITA" en la línea, etc. Ajusta a tu PDF real.
     */
    private function isPurchaseLine(string $line): bool
    {
        // Chequeo básico: empieza con dígito + espacio + M + cifra
        if (preg_match('/^\d+\s+M\d/', $line)) {
            // Y si incluye la palabra "PULPO" o algo similar
            // O si la estructura coincide con <precio> <total> <seller> al final
            return true;
        }
        return false;
    }

    /**
     * Si la siguiente línea es una sección nueva (p.e. "Total Pesca"), no la unimos
     */
    private function isLikelyNewSection(string $line): bool
    {
        // Podrías buscar algo como "Total Pesca", "IVA Pesca", "Servicios", ...
        $line = trim($line);
        if (
            str_starts_with($line, 'Fecha:') ||
            str_contains($line, 'Total Pesca') ||
            str_contains($line, 'Servicios') ||
            str_starts_with($line, 'Base% IVA') // etc...
        ) {
            return true;
        }
        return false;
    }

    private function startsWithDigit(string $line): bool
    {
        return preg_match('/^\d/', $line) === 1;
    }

    /**
     * parseProduct: si en "PULPO ABUELO PURGA816" quieres separar "PULPO" y "ABUELO PURGA816",
     * hazlo acá. De momento lo retornamos tal cual.
     */
    private function parseProduct(string $product): string
    {
        return trim($product);
    }
}

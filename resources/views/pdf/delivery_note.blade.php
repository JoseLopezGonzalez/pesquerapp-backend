<!DOCTYPE html>
<html>

<head>
    <title>Delivery Note</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'DejaVu Sans';
        }

        @page {
            size: A4 portrait;
        }

        .bold-first-line::first-line {
            font-weight: bold;
        }

        .break-before-page {
            page-break-before: always;
        }
    </style>
</head>

<body class="bg-white text-black text-xs">
    <div class="max-w-[210mm] mx-auto p-6 bg-white rounded min-h-screen">
        <!-- ENCABEZADO -->
        <div class="flex justify-between items-end mb-6">
            <div>
                <h1 class="text-lg font-bold">Congelados Brisamar S.L.</h1>
                <p>C/Dieciocho de Julio de 1922 Nº2 - 21410 Isla Cristina</p>
                <p>Tel: +34 613 09 14 94 </p>
                <p>administracion@congeladosbrisamar.es</p>
            </div>

            <div class="text-right">
                <h2 class="text-lg font-bold">Delivery Note</h2>
                <p class="font-medium">Pedido #: <span>{{ $order->formattedId }}</span></p>
                <p class="font-medium">Fecha: <span>{{ date('d/m/Y', strtotime($order->load_date)) }}</span></p>
                <p class="font-medium">Buyer Reference: {{ $order->buyer_reference }}</p>
            </div>

            <div class="flex flex-col items-center">
                <div class="p-1 border rounded bg-white">
                    <img alt="QR Code" 
                        src="{{ 'https://barcode.tec-it.com/barcode.ashx?data=Pedido%3A' . $order->id . '&code=QRCode&eclevel=L' }}"
                        class="w-16 h-16" />
                </div>
            </div>
        </div>

        <!-- DIRECCIONES (ENVÍO A LA IZQUIERDA, FACTURACIÓN A LA DERECHA) -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="border rounded-lg overflow-hidden bg-gray-50 p-4">
                <h3 class="font-bold mb-2">DIRECCIÓN DE ENVÍO</h3>
                <p>{!! nl2br(e($order->shipping_address)) !!}</p>
            </div>

            <div class="border rounded-lg overflow-hidden bg-gray-50 p-4 text-right">
                <h3 class="font-bold mb-2">DIRECCIÓN DE FACTURACIÓN</h3>
                <p>{!! nl2br(e($order->billing_address)) !!}</p>
            </div>
        </div>

        <!-- DETALLE DE PRODUCTOS -->
        <h3 class="font-bold mb-2">DETALLE DE PRODUCTOS</h3>
        <div class="border rounded-lg overflow-hidden">
            <table class="w-full text-xs">
                <thead class="border-b bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Producto</th>
                        <th class="p-2 text-center">Código GTIN</th>
                        <th class="p-2 text-center">Lote</th>
                        <th class="p-2 text-center">Cajas</th>
                        <th class="p-2 text-center">Peso Neto</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $rowIndex = 0; // Controlador manual para alternar colores
                    @endphp

                    @foreach ($order->productsWithLotsDetails as $productLine)
                        @php
                            $rowClass = $rowIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                            $rowIndex++; // Incrementamos el contador de filas
                        @endphp

                        <!-- Fila principal del producto -->
                        <tr class="{{ $rowClass }}">
                            <td class="p-2 py-1">{{ $productLine['product']['article']['name'] }}</td>
                            <td class="p-2 py-1">{{ $productLine['product']['boxGtin'] }}</td>
                            <td class="p-2 py-1">
                                {{ count($productLine['lots']) === 1 ? $productLine['lots'][0]['lot'] : '' }}
                            </td>
                            <td class="p-2 py-1">{{ $productLine['product']['boxes'] }}</td>
                            <td class="p-2 py-1">
                                {{ number_format($productLine['product']['netWeight'], 2, ',', '.') }} kg
                            </td>
                        </tr>

                        <!-- Fila con información de la especie -->
                        @php
                            $rowClass = $rowIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                            $rowIndex++;
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td class="pl-5 p-2 py-1 text-[10px]" colspan="5">
                                <i>
                                    {{ $productLine['product']['species']['name'] }}
                                    `{{ $productLine['product']['species']['scientificName'] }} -
                                    {{ $productLine['product']['species']['fao'] }}`
                                    - {{ $productLine['product']['fishingGear'] }} /
                                    {{ $productLine['product']['captureZone'] }}
                                </i>
                            </td>
                        </tr>

                        <!-- Si hay más de un lote, se imprimen en filas separadas -->
                        @if (count($productLine['lots']) > 1)
                            @foreach ($productLine['lots'] as $lot)
                                @php
                                    $rowClass = $rowIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                                    $rowIndex++;
                                @endphp
                                <tr class="{{ $rowClass }} text-[10px]">
                                    <td class="p-2 py-1"></td>
                                    <td class="text-md text-end">↪︎</td>
                                    <td class="p-2 py-1">{{ $lot['lot'] }}</td>
                                    <td class="p-2 py-1">{{ $lot['boxes'] }}</td>
                                    <td class="p-2 py-1">{{ number_format($lot['netWeight'], 2, ',', '.') }} kg</td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>

                <!-- TOTALES -->
                <tfoot class="border-t bg-gray-100">
                    <tr>
                        <td class="p-2 font-semibold">Total</td>
                        <td class="p-2 text-center"></td>
                        <td class="p-2 text-center"></td>
                        <td class="p-2 text-center">{{ $order->totalBoxes }}</td>
                        <td class="p-2 text-center">{{ number_format($order->totalNetWeight, 2, ',', '.') }} kg</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- INFORMACIÓN ADICIONAL -->
        <div class="grid grid-cols-2 gap-4 mt-6">
            <div class="border rounded-lg p-4 bg-gray-50">
                <h3 class="font-bold mb-2">INCOTERM</h3>
                <p><strong>{{ $order->incoterm->code }}</strong> ({{ $order->incoterm->description }})</p>
            </div>

            <div class="border rounded-lg p-4 bg-gray-50">
                <h3 class="font-bold mb-2">NÚMERO DE PALETS</h3>
                <p class="text-lg font-bold">{{ $order->numberOfPallets }}</p>
            </div>
        </div>

        <!-- NOTAS FINALES -->
        <div class="mt-6 text-center text-xs text-gray-600 italic">
            <p>Este documento ha sido generado electrónicamente y no requiere firma.</p>
        </div>
    </div>
</body>
</html>

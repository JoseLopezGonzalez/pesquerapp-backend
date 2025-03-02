<!-- resources/views/pdf/delivery_note.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Hoja de Pedido</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'DejaVu Sans';
        }

        .bold-first-line::first-line {
            font-weight: bold;
        }

        @page {
            size: A4 portrait;
        }
    </style>
</head>

<body class="h-full">
    <div class="flex flex-col max-w-[210mm] mx-auto p-6 bg-white rounded text-black text-xs min-h-screen">

        <!-- ENCABEZADO -->
        <div class="flex justify-between items-end mb-6">
            <div class="flex items-center gap-2">
                <div>
                    <h1 class="text-md font-bold">Congelados Brisamar S.L.</h1>
                    <p class="">C/Dieciocho de Julio de 1922 Nº2 - 21410 Isla Cristina</p>
                    <p class="">Tel: +34 613 09 14 94</p>
                    <p class="">administracion@congeladosbrisamar.es</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="text-end">
                    <h2 class="text-lg font-bold">Hoja de pedido</h2>
                    <p class="font-medium">{{ $order->formattedId }}</p>
                    <p class="font-medium">Fecha de Entrada: {{ date('d/m/Y', strtotime($order->entry_date)) }}</p>
                    <p class="font-medium">Fecha de Carga: {{ date('d/m/Y', strtotime($order->load_date)) }}</p>
                    <p class="font-medium">Buyer Reference: {{ $order->buyer_reference }}</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="p-1 border rounded flex items-center justify-center bg-white">
                        <img alt='Barcode Generator TEC-IT'
                            src="{{ 'https://barcode.tec-it.com/barcode.ashx?data=Pedido%3A' . $order->id . '&code=QRCode&eclevel=L' }}"
                            class="w-[4.1rem] h-[4.1rem]" />
                    </div>
                </div>
            </div>
        </div>

        <!-- INFORMACIÓN DEL CLIENTE Y DIRECCIONES -->
        <div class="grid grid-cols-2 gap-4 mb-6 text-xs">
            <div class="border rounded-lg overflow-hidden bg-gray-50">
                <div class="font-bold mb-2 w-full p-2 bg-gray-800 border-b text-white">DIRECCIÓN DE ENVÍO</div>
                <div class="space-y-1 p-4 pt-0">
                    <p>{!! nl2br($order->shipping_address) !!}</p>
                </div>
            </div>
            <div class="border rounded-lg overflow-hidden bg-gray-50">
                <div class="font-bold mb-2 w-full p-2 bg-gray-800 border-b text-white">DIRECCIÓN DE FACTURACIÓN</div>
                <div class="space-y-1 p-4 pt-0">
                    <p>{!! nl2br($order->billing_address) !!}</p>
                </div>
            </div>
        </div>

        <!-- DETALLE DE PRODUCTOS -->
        <div class="mb-6">
            <h3 class="font-bold mb-2">DETALLE DE PRODUCTOS</h3>
            <div class="border border-gray-300 rounded-lg overflow-hidden">
                <table class="w-full text-xs h-full">
                    <thead class="border-b bg-white">
                        <tr class="bg-gray-100">
                            <th class="p-2 font-bold text-start">Producto</th>
                            <th class="p-2 font-bold text-start">Código GTIN</th>
                            <th class="p-2 font-bold text-start">Lote</th>
                            <th class="p-2 font-bold text-start">Cajas</th>
                            <th class="p-2 font-bold text-start">Peso Neto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->productsWithLotsDetails as $productLine)
                            <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-50' }}">
                                <td class="p-2 py-1">{{ $productLine['product']['article']['name'] }}</td>
                                <td class="p-2 py-1">{{ $productLine['product']['boxGtin'] }}</td>
                                <td class="p-2 py-1">
                                    {{ count($productLine['lots']) === 1 ? $productLine['lots'][0]['lot'] : '' }}</td>
                                <td class="p-2 py-1">{{ $productLine['product']['boxes'] }}</td>
                                <td class="p-2 py-1">
                                    {{ number_format($productLine['product']['netWeight'], 2, ',', '.') }} kg</td>
                            </tr>
                            @if (count($productLine['lots']) > 1)
                                @foreach ($productLine['lots'] as $lot)
                                    <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-50' }} text-[10px]">
                                        <td class="p-2 py-1"></td>
                                        <td class="text-md text-end">↪︎</td>
                                        <td class="p-2 py-1">{{ $lot['lot'] }}</td>
                                        <td class="p-2 py-1">{{ $lot['boxes'] }}</td>
                                        <td class="p-2 py-1">{{ number_format($lot['netWeight'], 2, ',', '.') }} kg
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECCIÓN DE DATOS ADICIONALES -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="border p-4 rounded-lg bg-gray-50">
                <h3 class="font-bold mb-1">INCOTERM</h3>
                <p>{{ $order->incoterm->code }} - {{ $order->incoterm->description }}</p>
            </div>
            <div class="border p-4 rounded-lg bg-gray-50">
                <h3 class="font-bold mb-1">FORMA DE PAGO</h3>
                <p>{{ $order->payment_term->name }}</p>
            </div>
            <div class="border p-4 rounded-lg bg-gray-50">
                <h3 class="font-bold mb-1">NÚMERO DE PALETS</h3>
                <p>{{ $order->numberOfPallets }}</p>
            </div>
        </div>

        <!-- OBSERVACIONES -->
        <div class="border p-4 rounded-lg bg-gray-50">
            <h3 class="font-bold mb-2">OBSERVACIONES</h3>
            <p><strong>Producción:</strong> {{ $order->production_notes }}</p>
            <p><strong>Contabilidad:</strong> {{ $order->accounting_notes }}</p>
            <p><strong>Transporte:</strong> {{ $order->transportation_notes }}</p>
        </div>

    </div>
</body>

</html>

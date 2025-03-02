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

        <!-- DIRECCIONES -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="border rounded-lg overflow-hidden bg-gray-50 p-4">
                <h3 class="font-bold mb-2">DATOS DEL CLIENTE</h3>
                <p><span class="font-medium">Nombre:</span> {{ $order->customer->name }}</p>
                <p><span class="font-medium">NIF/CIF:</span> {{ $order->customer->vat_number }}</p>
                <p class="font-medium mt-2">Correos electrónicos:</p>
                <ul class="list-disc pl-5">
                    @foreach ($order->emailsArray as $email)
                        <li>{{ $email }}</li>
                    @endforeach
                    @foreach ($order->ccEmailsArray as $email)
                        <li>{{ $email }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="border rounded-lg overflow-hidden bg-gray-50 p-4">
                <h3 class="font-bold mb-2">DIRECCIÓN DE ENTREGA</h3>
                <p>{!! nl2br(e($order->shipping_address)) !!}</p>
            </div>
        </div>

        <!-- DETALLE DE PRODUCTOS -->
        <h3 class="font-bold mb-2">DETALLE DE PRODUCTOS</h3>
        <div class="border rounded-lg overflow-hidden">
            <table class="w-full text-xs">
                <thead class="border-b bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Producto</th>
                        <th class="p-2 text-center">Cajas</th>
                        <th class="p-2 text-center">Peso Neto</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $rowIndex = 0;
                    @endphp

                    @foreach ($order->productsBySpeciesAndCaptureZone as $productsBySpeciesAndCaptureZone)
                        @foreach ($productsBySpeciesAndCaptureZone['products'] as $product)
                            @php
                                $rowClass = $rowIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                                $rowIndex++;
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td class="p-2">{{ $product['product']->article->name }}</td>
                                <td class="p-2 text-center">{{ $product['boxes'] }}</td>
                                <td class="p-2 text-center">{{ number_format($product['netWeight'], 2, ',', '.') }} kg</td>
                            </tr>
                        @endforeach

                        <tr class="bg-gray-50 text-[10px] italic">
                            <td class="p-2" colspan="3">
                                {{ $productsBySpeciesAndCaptureZone['species']->scientific_name }}
                                ({{ $productsBySpeciesAndCaptureZone['species']->fao }}) -
                                {{ $productsBySpeciesAndCaptureZone['captureZone']->name }} -
                                Caught with: {{ $productsBySpeciesAndCaptureZone['species']->fishingGear->name }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                <!-- TOTALES -->
                <tfoot class="border-t bg-gray-100">
                    <tr>
                        <td class="p-2 font-semibold">Total</td>
                        <td class="p-2 text-center">{{ $order->totals['boxes'] }}</td>
                        <td class="p-2 text-center">{{ number_format($order->totals['netWeight'], 2, ',', '.') }} kg</td>
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

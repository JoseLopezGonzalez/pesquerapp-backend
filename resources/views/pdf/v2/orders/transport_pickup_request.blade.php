<!DOCTYPE html>
<html>

<head>
    <title>Solicitud de recogida de transporte</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'DejaVu Sans';
        }

        @page {
            size: A4 portrait;
        }
    </style>
</head>

<body class="bg-white text-black text-xs">
    <div class="max-w-[210mm] mx-auto p-6 bg-white rounded min-h-screen">

        <!-- ENCABEZADO -->
        <div class="flex justify-between items-end mb-6">
            <div>
                <h1 class="text-md font-bold">Congelados Brisamar S.L.</h1>
                <p>C/Dieciocho de Julio de 1922 Nº2 - 21410 Isla Cristina</p>
                <p>Tel: +34 613 09 14 94</p>
                <p>administracion@congeladosbrisamar.es</p>
            </div>
            <div class="flex items-start gap-4">
                <div class="text-end">
                    <h2 class="text-lg font-bold">Solicitud de recogida de transporte</h2>
                    <p class="font-medium">{{ $entity->formattedId }}</p>
                    <p class="font-medium">Buyer Reference: {{ $entity->buyer_reference }}</p>
                </div>
                <div class="p-1 border rounded bg-white">
                    <img src="{{ 'https://barcode.tec-it.com/barcode.ashx?data=Pedido%3A' . $entity->id . '&code=QRCode&eclevel=L' }}"
                        class="w-[4.1rem] h-[4.1rem]" alt="QR Code" />
                </div>
            </div>
        </div>


        <div class='grid-cols-2 grid gap-4'>


            <div class="mt-6 p-4 bg-gray-50 rounded-lg border text-xs space-y-3">
                <p><strong>Fecha de recogida prevista:</strong></p>
                <p>{{ date('d/m/Y', strtotime($entity->load_date)) }}</p>
            </div>

            <div class="mt-6 p-4 bg-gray-50 rounded-lg border text-xs space-y-3">
                <p><strong>Nº de Palets Previstos:</strong></p>
                <p> {{ $entity->numberOfPallets }}</p>
            </div>


        </div>





        <!-- DATOS DEL TRANSPORTE + CONTACTOS -->
        <div class="grid grid-cols-2 gap-4 mb-6 w-full">
            <div class="border rounded-lg bg-gray-50">
                <div class="font-bold p-2 bg-gray-800 text-white">TRANSPORTE</div>
                <div class="p-4 space-y-1">
                    <p><span class="font-medium">Empresa:</span> {{ $entity->transport->name }}</p>
                    <p class="font-medium">Correos electrónicos:</p>
                    <ul class="list-disc pl-5">
                        @foreach ($entity->transport->emailsArray as $email)
                            <li>{{ $email }}</li>
                        @endforeach
                        @foreach ($entity->ccEmailsArray as $email)
                            <li>{{ $email }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="border rounded-lg bg-gray-50">
                <div class="font-bold p-2 bg-gray-800 text-white">CONTACTOS IMPORTANTES</div>
                <div class="p-4 space-y-1">
                    <p><span class="font-medium">Contacto emergencias:</span> emergencias@congeladosbrisamar.es</p>
                    <p><span class="font-medium">Contacto incidencias:</span> incidencias@congeladosbrisamar.es</p>
                    <p><span class="font-medium">Contacto para carga:</span> carga@congeladosbrisamar.es</p>
                    <p><span class="font-medium">Contacto para descarga:</span> descarga@congeladosbrisamar.es</p>
                </div>
            </div>
        </div>

        <!-- DIRECCIÓN DE RECOGIDA Y ENTREGA -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="border rounded-lg bg-gray-50">
                <div class="font-bold p-2 bg-gray-800 text-white">DIRECCIÓN DE RECOGIDA</div>
                <div class="p-4">{!! nl2br(e($entity->shipping_address)) !!}</div>
            </div>
            <div class="border rounded-lg bg-gray-50">
                <div class="font-bold p-2 bg-gray-800 text-white">DIRECCIÓN DE ENTREGA</div>
                <div class="p-4">{!! nl2br(e($entity->billing_address)) !!}</div>
            </div>
        </div>

        <!-- DETALLE DE PRODUCTOS -->
        <h3 class="font-bold mb-2">DETALLE DE PRODUCTOS</h3>
        <div class="border rounded-lg overflow-hidden mb-6">
            <table class="w-full text-xs">
                <thead class="border-b bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Producto</th>
                        <th class="p-2 text-center">Cajas</th>
                        <th class="p-2 text-center">Peso Neto (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entity->plannedProductDetails as $detail)
                        <tr>
                            <td class="p-2">{{ $detail->product->article->name }}</td>
                            <td class="p-2 text-center">{{ $detail->boxes }}</td>
                            <td class="p-2 text-center">{{ number_format($detail->quantity, 2, ',', '.') }} kg</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- OBSERVACIONES -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg border text-xs space-y-3">
            <p><strong>Observaciones para el transportista:</strong></p>
            <p>{!! nl2br(e($entity->transportation_notes)) !!}</p>
        </div>

        <!-- ESPACIO PARA FIRMA Y SELLO CENTRADO -->
        <div class="mt-10 flex justify-center">
            <div class="border rounded-lg p-6 h-[130px] w-[320px] flex flex-col justify-between">
                <p class="font-bold text-center uppercase">Firma, sello y fecha del transportista</p>
                <div class="border-t border-gray-400 mt-4"></div>
            </div>
        </div>

    </div>
</body>

</html>

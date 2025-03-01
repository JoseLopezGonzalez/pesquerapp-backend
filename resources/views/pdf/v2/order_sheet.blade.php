<!-- resources/views/pdf/delivery_note.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Hoja de Pedido</title>
    {{-- Tailwind no funciona, lo cojo todo directamente de un cdn --}}

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'DejaVu Sans';
        }

        .bold-first-line::first-line {
            font-weight: bold;
        }
    </style>
    {{-- getProductsWithLotsDetailsBySpeciesAndCaptureZoneAttribute --}}
</head>

<body>

    <div class="flex flex-col max-w-[210mm]  mx-auto p-6 bg-white rounded text-black text-xs h-full">
        <div class="flex justify-between items-start mb-6 ">
            <div class="flex items-center gap-2">
                <div>
                    <h1 class="text-lg font-bold ">Congelados Brisamar S.L.</h1>
                    <p class=" ">C/Dieciocho de Julio de 1922 Nº2 - 21410 Isla Cristina</p>
                    <p class=" ">Tel: +34 613 09 14 94 - administracion@congeladosbrisamar.es</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="  rounded  text-end">
                    <h2 class="text-lg font-bold ">PEDIDO</h2>
                    <p class=" font-medium"><span class="">{{ $order->formattedId }}</span></p>
                    <p class=" font-medium">Fecha: 02/02/2025 <span class="">{{ $order->load_date }}</span></p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="p-1 border rounded flex items-center justify-center bg-white">
                        <img alt='Barcode Generator TEC-IT'
                            src='https://barcode.tec-it.com/barcode.ashx?data=Pedido%3A123654&code=QRCode&eclevel=L'
                            class="w-[3.5rem] h-[3.5rem]" />
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-6 text-xs">
            <div class="space-y-4">
                <div class="border rounded p-4">
                    <h3 class="font-bold  mb-2">DATOS DEL CLIENTE</h3>
                    <div class=" space-y-1">
                        <p><span class="font-medium">Nombre:</span> Distribuciones Marítimas S.A.</p>
                        <p><span class="font-medium">NIF/CIF:</span> B-12345678</p>
                        <p><span class="font-medium">Buyer Reference:</span> BUY-2025-0789</p>
                        <p class="font-medium mt-2">Correos electrónicos:</p>
                        <ul class="list-disc pl-5">
                            <li>pedidos@distribuciones-maritimas.com</li>
                            <li>logistica@distribuciones-maritimas.com</li>
                            <li>facturacion@distribuciones-maritimas.com</li>
                        </ul>
                    </div>
                </div>
                <div class="border rounded p-4">
                    <h3 class="font-bold  mb-2">DATOS DE TRANSPORTE</h3>
                    <div class=" space-y-1">
                        <p><span class="font-medium">Empresa:</span> Transportes Rápidos del Norte S.L.</p>
                        <p class="font-medium mt-2">Correos electrónicos:</p>
                        <ul class="list-disc pl-5">
                            <li>pedidos@distribuciones-maritimas.com</li>
                            <li>logistica@distribuciones-maritimas.com</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="border rounded p-4">
                <h3 class="font-bold  mb-2">DIRECCIÓN DE FACTURACIÓN</h3>
                <p class="">Polígono Industrial La Marina, Nave 12</p>
                <p class="">48950 Erandio, Vizcaya</p>
                <p class="">España</p>
                <hr class="my-4 border-dashed border-slate-300" />
                <h3 class="font-bold  mb-2">DIRECCIÓN DE ENVÍO</h3>
                <p class="">Mercado Central de Abastos</p>
                <p class="">Puesto 45-48</p>
                <p class="">48002 Bilbao, Vizcaya</p>
                <p class="">España</p>
            </div>
        </div>
        <div class="flex-1 mb-6 flex flex-col h-full">
            <h3 class="font-bold mb-2">DETALLE DE PRODUCTOS</h3>
            <div class="border rounded overflow-hidden p-4 flex-1">
                <table class="w-full  text-xs h-full">
                    <thead class=" border-b ">
                        <tr>
                            <th class="p-1 font-medium text-start">Producto</th>
                            <th class="p-1 font-medium text-start">Código GTIN</th>
                            <th class="p-1 font-medium text-start">Lote</th>
                            <th class="p-1 font-medium text-start">Cajas</th>
                            <th class="p-1 font-medium text-start">Peso Neto</th>
                        </tr>
                    </thead>
                    <tbody class="h-full align-top">
                        @foreach ($order->productsWithLotsDetails as $productLine)
                            @if (count($productLine['lots']) == 1)
                                <tr>
                                    <td class=" p-1">{{ $productLine['product']['article']['name'] }}</td>
                                    <td class=" p-1">{{ $productLine['product']['boxGtin'] }}</td>
                                    <td class=" p-1">{{ $productLine['lots'][0]['lot'] }}</td>
                                    <td class=" p-1">{{ $productLine['product']['boxes'] }}</td>
                                    <td class=" p-1">{{ $productLine['product']['netWeight'] }} kg</td>
                                </tr>
                                <tr>
                                    <td class="pl-5 p-1 text-[10px]" colspan="5">
                                        hola
                                        <i>
                                            {{ $productLine['product']['species']['name'] }}
                                            `{{ $productLine['product']['species']['scientificName'] }} -
                                            {{ $productLine['product']['species']['fao'] }}`
                                            - {{ $productLine['product']['fishingGear'] }} /
                                            {{ $productLine['product']['captureZone'] }}
                                        </i>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class=" p-1">{{ $productLine['product']['article']['name'] }}</td>
                                    <td class=" p-1">{{ $productLine['product']['boxGtin'] }}</td>
                                    <td class=" p-1"></td>
                                    <td class=" p-1">{{ $productLine['product']['boxes'] }}</td>
                                    <td class=" p-1">{{ $productLine['product']['netWeight'] }} kg</td>
                                </tr>
                                <tr>
                                    <td class="pl-5 p-1 text-[10px]" colspan="5">
                                        <i>
                                            {{ $productLine['product']['species']['name'] }}
                                            `{{ $productLine['product']['species']['scientificName'] }} -
                                            {{ $productLine['product']['species']['fao'] }}`
                                            - {{ $productLine['product']['fishingGear'] }} /
                                            {{ $productLine['product']['captureZone'] }}
                                        </i>
                                    </td>
                                </tr>
                                @foreach ($productLine['lots'] as $lot)
                                    <tr class="text-[10px]">
                                        <td class=" p-1"></td>
                                        <td class=" text-md text-end">↪︎</td>
                                        <td class=" p-1">{{ $lot['lot'] }}</td>
                                        <td class=" p-1">{{ $lot['boxes'] }}</td>
                                        <td class=" p-1">{{ $lot['netWeight'] }} kg</td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr className='font-bold py-2'>
                                <td class="p-1 "></td>
                                <td class="p-1"></td>
                                <td class="p-1">Total</td>
                                <td class="p-1 ">{{ $order->totalBoxes }} </td>
                                <td class="p-1 "> {{ $order->totalNetWeight }} kg</td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
        </div>
        <hr class="my-4" />
        <div class="flex justify-between items-end">
            <p>Documento generado electrónicamente. No requiere firma.</p>
            <p>Ref: 2341434-231143 </p>
        </div>
    </div>
</body>

</html>

<!-- resources/views/pdf/delivery_note.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Packing List</title>
    {{-- Tailwind no funciona, lo cojo todo directamente de un cdn --}}

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
            /*  margin: 20mm; */
        }
    </style>
    {{-- getProductsWithLotsDetailsBySpeciesAndCaptureZoneAttribute --}}
</head>

<body class="h-full">

    <div class="flex flex-col max-w-[210mm]  mx-auto p-6 bg-white rounded text-black text-xs min-h-screen ">
        <div class="flex justify-between items-start mb-6 ">
            <div class="flex items-center gap-2">
                <div>
                    <h1 class="text-lg font-bold ">Congelados Brisamar S.L.</h1>
                    <p class=" ">C/Dieciocho de Julio de 1922 Nº2 - 21410 Isla Cristina</p>
                    <p class=" ">Tel: +34 613 09 14 94 </p>
                    <p class=" ">administracion@congeladosbrisamar.es</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="  rounded  text-end">
                    <h2 class="text-lg font-bold ">PEDIDO</h2>
                    <p class=" font-medium"><span class="">{{ $order->formattedId }}</span></p>
                    <p class=" font-medium">Fecha:<span class="">
                            {{ date('d/m/Y', strtotime($order->load_date)) }}
                        </span></p>
                    <p class=" font-medium">Buyer Reference:{{ $order->buyer_reference }}</p>
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
        

        <div className="mb-6">
                @foreach ($order->pallets as $pallet)
                <div  className="mb-8">
                    <div className="bg-gray-800 text-white p-2 flex justify-between items-center">
                        <h3 className="font-bold text-lg">PALET {{ $pallet->number }}</h3>
                        <div className="text-sm">
                            <span className="mr-4">Peso Neto: {{ $pallet->netWeight }} kg</span>
                            <span>Cajas: {{$pallet->numberOfBoxes}} </span>
                        </div>
                    </div>

                    <div className="border border-gray-300 border-t-0 p-2 mb-2 bg-gray-50">
                        <span className="font-semibold">Lotes en este palet: </span>
                        @foreach ($pallet->lots as $lot)
                            <span  className="inline-block bg-gray-200 px-2 py-1 text-sm mr-2 mb-1">
                                {{ $lot }}
                            </span>
                        @endforeach
                    </div>

                    <table className="w-full border-collapse mb-2">
                        <thead>
                            <tr className="bg-gray-100">
                                <th className="border border-gray-300 p-2 text-left">Producto</th>
                                <th className="border border-gray-300 p-2 text-center">Cajas</th>
                                <th className="border border-gray-300 p-2 text-center">Peso Total (kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pallet->summary as $productDetail)
                                <tr className="{{ $loop->even ? 'bg-white' : 'bg-gray-50' }}">
                                    <td className="border border-gray-300 p-2 font-semibold">{{ $productDetail['product']->article->name }}</td>
                                    <td className="border border-gray-300 p-2 text-center">{{ $productDetail['boxes'] }}</td>
                                    <td className="border border-gray-300 p-2 text-center">{{ $productDetail['netWeight'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- <div className="grid grid-cols-2 gap-4 mt-2 mb-4">
                        {pallet.products.map((product, i) => (
                            <div key={i} className="border border-gray-300 p-2 bg-white">
                                <p className="text-xs mb-1 font-semibold">
                                    {product.name} - Lote: {product.lot}
                                </p>
                                <div className="h-10 bg-gray-800 relative flex items-center justify-center">
                                    <div className="absolute inset-0 flex items-center">
                                        {/* Simulación de código de barras con líneas verticales */}
                                        {Array.from({ length: 30 }).map((_, i) => (
                                            <div
                                                key={i}
                                                className="h-full"
                                                style={{
                                                    width: `${Math.random() * 3 + 1}px`,
                                                    backgroundColor: "white",
                                                    marginLeft: `${Math.random() * 3 + 1}px`,
                                                }}
                                            />
                                        ))}
                                    </div>
                                    <span className="text-xs text-white z-10 bg-gray-800 px-1">GS1-128</span>
                                </div>
                                <p className="text-xs mt-1 text-center">{product.lot}</p>
                            </div>
                        ))}
                    </div> --}}

                    {{--  NUEVO: Listado detallado de cajas  --}}
                    <div className="mt-4 mb-4">
                        <h4 className="font-bold text-sm bg-gray-200 p-2 border-t border-l border-r border-gray-300">
                            LISTADO DETALLADO DE CAJAS - PALET #{{ $pallet->id}}
                        </h4>
                        <div className="border border-gray-300 p-2 max-h-60 overflow-y-auto">
                            <table className="w-full border-collapse text-sm">
                                <thead className="sticky top-0 bg-white">
                                    <tr className="bg-gray-100">
                                        <th className="border border-gray-300 p-1 text-left">ID Caja</th>
                                        <th className="border border-gray-300 p-1 text-left">Producto</th>
                                        <th className="border border-gray-300 p-1 text-center">Peso (kg)</th>
                                        <th className="border border-gray-300 p-1 text-center">Lote</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pallet->boxes as $box)
                                        <tr className="{{ $loop->even ? 'bg-white' : 'bg-gray-50' }}">
                                            <td className="border border-gray-300 p-1 font-mono">{{ $box->box->id }}</td>
                                            <td className="border border-gray-300 p-1">{{ $box->box->product->article->name }}</td>
                                            <td className="border border-gray-300 p-1 text-center">{{ $box->box->net_weight }}</td>
                                            <td className="border border-gray-300 p-1 text-center font-mono text-xs">{{ $box->box->lot }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            
                @endforeach
        </div>


    </div>
</body>

</html>

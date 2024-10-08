<!-- resources/views/pdf/delivery_note.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Orders Signs</title>
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
            size: landscape;
            /* margin: 10mm 30mm 10mm 10mm;  */
            /* top, right, bottom, left */
        }

        /*  table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; } */
    </style>
</head>

<body>
    @foreach ($order->pallets as $pallet)
        <div class="flex flex-col h-svh border-2 border-black">
            <div class="w-full text-center p-5 border-2 border-black">
                <h1 class="font-bold text-5xl">{{ $order->customer->alias }}</h1>
                <h3 class="font-medium text-3xl">{{ $order->formattedId }} - Pallet Nº {{ $pallet->id }}</h3>
            </div>
            <div class="grid grid-cols-2 text-lg">
                <div class="border-2 border-black p-5">
                    <p class="font-bold"><u>Expedidor:</u></p>
                    <p><strong>LA PESCA DEL MERIDION, S.L.U.</strong><br>PATIO DEL PENINSULAR, 19</br>21409 - AYAMONTE</br>HUELVA - ESPAÑA</p>
                </div>
                <div class="border-2 border-black p-5">
                    <p class="font-bold"><u>Lugar de entrega:</u></p>
                    <p class="preserve-line-breaks">
                        <b>
                        {{ $order->customer->alias }}
                        </b>
                            <br />
                            {{-- Delete first line of Text of shipping Address y respetar los saltos de lineas del texto original con nl2br --}}

                            @php
                                // Separamos el texto en líneas
                                $addressLines = explode("\n", $order->shipping_address);
                                // Quitamos la primera línea
                                array_shift($addressLines);
                                // Unimos nuevamente el texto, excluyendo la primera línea
                                $modifiedAddress = implode("\n", $addressLines);
                            @endphp

                            {!! nl2br(e($modifiedAddress)) !!}


                    </p>
                </div>
            </div>
            <div class="p-3 grow flex items-center border-2 border-black">
                <div class="w-full" style="line-height: 100%;">
                    <table class="w-full text-sm">
                        <tbody>
                            <tr class="text-2xl">
                                <th class="text-start font-medium p-1.5">Productos de la pesca</th>
                                <td class="text-center">{{ $pallet->totals['boxes'] }} /cajas</td>
                                <td class="text-center">{{ number_format($pallet->totals['netWeight'], 2, ',', '.') }}
                                    kg</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            
        </div>
    @endforeach


</body>

</html>

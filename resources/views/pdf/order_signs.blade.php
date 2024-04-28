<!-- resources/views/pdf/delivery_note.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Delivery Note </title>
    {{-- Tailwind no funciona, lo cojo todo directamente de un cdn --}}

    <script src="https://cdn.tailwindcss.com"></script>

   

    <style>
        body { font-family: 'DejaVu Sans'; }
        .bold-first-line::first-line {
            font-weight: bold;
        }

        @page {
            size: A4 landscape;
            /* margin: 10mm 30mm 10mm 10mm;  *//* top, right, bottom, left */
        }
       /*  table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; } */
        
    </style>
</head>
<body>
    @foreach ($order->pallets as $pallet)
    <div class="flex flex-col h-screen border-2 border-black">
        <div class="w-full text-center p-5 border-2 border-black">
            <h1 class="font-bold text-5xl">{{ $order->customer->alias }}</h1>
            <h3 class="font-medium text-3xl">#{{ $order->formattedId }} - Pallet Nº {{ $pallet->id }}</h3>
        </div>
        <div class="grid grid-cols-2 text-lg">
            <div class="border-2 border-black p-5">
                <p class="font-bold"><u>Expedidor:</u></p>
                <p>Congelados Brisamar S.L.</p>
                <p>Poligono Vista Hermosa, Nave 11A<br>21410 ISLA CRISTINA<br>HUELVA – ESPAÑA</p>
            </div>
            <div class="border-2 border-black p-5">
                <p class="font-bold"><u>Lugar de entrega:</u></p>
                <p class="preserve-line-breaks">
                    {{ $order->customer->alias }}
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
                            <th class="text-start font-medium p-1.5">Pulpo Fresco</th>
                            {{-- <td class="text-center">{{ count($pallet['boxes']) }} /cajas</td>
                            <td class="text-center">{{ number_format($pallet['netWeight'], 2) }} kg</td> --}}
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="text-center p-4 border-2 border-black">
            {{-- <h1 class="font-bold text-8xl p-4">{{ strtoupper($oder->shipping_address)) }}</h1> --}}
            <h1 class="font-bold text-7xl">{{ $order->transport->name }}</h1>
        </div>
    </div>
    @endforeach
    
    
</body>
</html>

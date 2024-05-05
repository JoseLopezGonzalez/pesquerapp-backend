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
            
            margin: 1mm 1mm 1mm 1mm; 
            /* top, right, bottom, left */
        }
       /*  table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; } */
        
    </style>
</head>
<body>
   
    

@php
    $imgs = ['cmr-page-1.png', 'cmr-page-2.png', 'cmr-page-3.png', 'cmr-page-4.png'];
@endphp




    <div>
        @foreach ($imgs as $index => $img)
            <div class="imprimir  h-screen " >
                <div class="text-center text-uppercase h-full" style="position: relative;">
                    <div class=" flex items-center justify-center h-full overflow-hidden w-full" style="position: absolute;  ">
                        <img src="{{ asset('images/documents/CMR/' . $img) }}" class="h-full" />

                    </div>
                    <p style="text-align: left; font-size: 9pt; left: 90px; top: 70px; position: absolute;">
                        CONGELADOS BRISAMAR S.L.<br />
                        POLIGONO VISTA HERMOSA, N11A<br />
                        21410 ISLA CRISTINA<br />
                        HUELVA - ESPAÑA
                    </p>
                    <p style="font-weight: bold; text-align: left; font-size: 9pt; left: 665px; top: 60px; position: absolute;">{{ $order->formattedId }}</p>
                    <p class="preserve-line-breaks" style="text-align: left; font-size: 8pt; left: 420px; top: 180px; position: absolute;">
                        {{ $order->transport->name }}
                        {{ $order->transport->address }}
                    </p>
                    <p class="preserve-line-breaks" style="text-align: left; font-size: 7pt; left: 90px; top: 168px; position: absolute;">
                        {{ $order->customer->alias }} <br/>
                       {{ !! nl2br(e($order->billing_address)) !! }}
                    </p>
                    <p class="preserve-line-breaks" style="text-align: left; font-size: 6pt; left: 90px; top: 260px; position: absolute;">
                        {{ $order->customer->alias }} <br/>
                        
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
                    <p style="text-align: left; font-size: 9pt; left: 90px; top: 340px; position: absolute;">
                        ISLA CRISTINA - HUELVA
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 310px; top: 312px; position: absolute;">
                        {{ date('d/m/Y', strtotime($order->load_date)) }}
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 90px; top: 405px; position: absolute;">
                        ALBARÁN {{ $order->formattedId }}
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 55px; top: 480px; position: absolute;">
                        {{ $order->numberOfPallets }} palets
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 180px; top: 480px; position: absolute;">
                        {{ $order->totals['boxes'] }} cajas
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 280px; top: 480px; position: absolute;">
                        CAJAS
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 350px; top: 480px; position: absolute;">
                        PRODUCTOS DE LA PESCA
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 580px; top: 480px; position: absolute;">
                        {{ number_format($order->totals['netWeight'], 2) }} kg
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 190px; top: 725px; position: absolute;">
                        0 ºC
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 160px; top: 905px; position: absolute;">
                        ISLA CRISTINA
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 290px; top: 905px; position: absolute;">
                        {{ $order->load_date }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>


    
</body>
</html>

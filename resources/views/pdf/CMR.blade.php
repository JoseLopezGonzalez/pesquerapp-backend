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
            <div class="imprimir h-svh " >
                <div class="text-center text-uppercase" style="position: relative;">
                    <div style="position: absolute;">
                        <img src="{{ asset('images/documents/CMR/' . $img) }}" width="100%" />

                    </div>
                    <p style="text-align: left; font-size: 9pt; left: 65px; top: 80px; position: absolute;">
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
                    <p class="preserve-line-breaks" style="text-align: left; font-size: 7pt; left: 65px; top: 178px; position: absolute;">
                        {{ $order->customer->alias }} <br/>
                       {{--  {{ TextHelper::deleteFirstLineOfText($order->billing_address) }} --}}
                    </p>
                    <p class="preserve-line-breaks" style="text-align: left; font-size: 6pt; left: 65px; top: 270px; position: absolute;">
                        {{ $order->customer->alias }} <br/>
                        {{-- {{ TextHelper::deleteFirstLineOfText($order->shipping_address) }} --}}
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 65px; top: 350px; position: absolute;">
                        ISLA CRISTINA - HUELVA
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 310px; top: 322px; position: absolute;">
                        {{ $order->load_date }}
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 65px; top: 415px; position: absolute;">
                        ALBARÁN {{ $order->formattedId }}
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 55px; top: 490px; position: absolute;">
                        {{ $order->numberOfPallets }} palets
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 180px; top: 490px; position: absolute;">
                        {{ $order->totals['boxes'] }} cajas
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 280px; top: 490px; position: absolute;">
                        CAJAS
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 350px; top: 490px; position: absolute;">
                        PRODUCTOS DE LA PESCA
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 580px; top: 490px; position: absolute;">
                        {{ number_format($order->totals['netWeight'], 2) }} kg
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 190px; top: 735px; position: absolute;">
                        0 ºC
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 160px; top: 915px; position: absolute;">
                        ISLA CRISTINA
                    </p>
                    <p style="text-align: left; font-size: 9pt; left: 290px; top: 915px; position: absolute;">
                        {{ $order->load_date }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>


    
</body>
</html>

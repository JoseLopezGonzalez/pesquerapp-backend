<!-- resources/views/pdf/delivery_note.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Delivery Note </title>
    {{-- Tailwind no funciona, lo cojo todo directamente de un cdn --}}

    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

   

    <style>
        body { font-family: 'DejaVu Sans'; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        /* Tailwind */
        .pt-4 { padding-top: 1rem; }
    .grid { display: grid; }
    .grid-cols-12 { grid-template-columns: repeat(12, minmax(0, 1fr)); }
    .col-span-3 { grid-column: span 3; }
    .col-span-4 { grid-column: span 4; }
    .col-span-5 { grid-column: span 5; }
    .col-span-7 { grid-column: span 7; }
    .col-span-8 { grid-column: span 8; }
    .col-span-10 { grid-column: span 10; }
    .col-span-12 { grid-column: span 12; }
    .text-right { text-align: right; }
    .text-left { text-align: left; }
    .text-center { text-align: center; }
    .text-sm { font-size: 0.875rem; }
    .font-medium { font-weight: 500; }
    .border-b { border-bottom: 1px solid #e2e8f0; }
    .border-b-2 { border-bottom: 2px solid black; }
    .w-full { width: 100%; }
    .mt-2 { margin-top: 0.5rem; }
    .mt-3 { margin-top: 0.75rem; }
    .mt-8 { margin-top: 2rem; }
    .mb-4 { margin-bottom: 1rem; }
    .p-1.5 { padding: 0.375rem; }
    .p-2 { padding: 0.5rem; }
    </style>
</head>
<body>
    <div class="pt-4">
        <div class="grid grid-cols-12" style="margin-bottom: 1rem;">
            <div class="col-span-4">
                <img src="https://congeladosbrisamar.es/logo2" alt="Logo" style="max-width: 50px;">
            </div>
            <div class="col-span-8" style="line-height: 122%; text-align: right; color: #1E79BB;">
                <p style="font-size: 10pt;">
                    <strong>CONGELADOS BRISAMAR S.L.</strong><br>
                    C.I.F.: B-215 732 82<br>
                    Poligono vista hermosa, nave 11A<br>
                    21410 Isla Cristina Huelva
                </p>
            </div>
        </div>
        <div class="grid grid-cols-12" style="width:100%; margin-bottom: 1rem;">
            <!-- Color bars across the top -->
            <div class="col-span-3" style="width:25%; padding-right: 0; padding-left: 0; height: 0.1rem; background-color: #06d6ff;"></div>
            <div class="col-span-3" style="width:25%; padding-right: 0; padding-left: 0; height: 0.1rem; background-color: #079def;"></div>
            <div class="col-span-3" style="width:25%; padding-right: 0; padding-left: 0; height: 0.1rem; background-color: #d1d1d1;"></div>
            <div class="col-span-3" style="width:25%; padding-right: 0; padding-left: 0; height: 0.1rem; background-color: black;"></div>
        </div>
        <div class="grid grid-cols-12 mt-2 mb-4">
            <div class="col-span-12">
                <p style="margin-top: 1.2rem; font-size: 1.5rem;"><strong>DELIVERY NOTE</strong></p>
            </div>
            <div class="col-span-5 mt-3">
                <table class="w-full">
                    <tbody>
                        <tr class="border-b border-gray-200">
                            <th class="text-left font-medium text-sm p-2">Number</th>
                            <td class="text-left text-sm">{{ $order->id }}</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <th class="text-left font-medium text-sm p-2">Date</th>
                            <td class="text-left text-sm">{{ $order->load_date }}</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <th class="text-left font-medium text-sm p-2">Buyer Reference</th>
                            <td class="text-left text-sm">{{ $order->buyer_reference }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-span-7 text-right mt-2" style="line-height: 100%;">
                <p class="cliente preserve-line-breaks bold-first-line" style="font-size: 0.9rem;">
                    {!! nl2br($order->billing_address) !!}
                </p>
            </div>
        </div>
        <div class="w-full mt-12">
            <table class="w-full text-sm">
                <thead class="border-b-2 border-black">
                    <tr>
                        <th class="text-left p-1.5">Item</th>
                        <th class="text-center">Boxes</th>
                        <th class="text-center">Weight</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->productsBySpeciesAndCaptureZone as $productsBySpeciesAndCaptureZone)
                        @foreach($productsBySpeciesAndCaptureZone['products'] as $product)
                            <tr class="border-b border-gray-200">
                                <th class="text-left font-medium p-1.5">{{ $product['product']->article->name}}</th>
                                <td class="text-center">{{ $product['boxes'] }}</td>
                                <td class="text-center">{{ number_format($product['netWeight'], 2) }} kg</td>
                            </tr>
                            

                        @endforeach

                        <tr class="border-b border-gray-200" style="font-size: 5px">
                            <th class="text-left  p-1.5" style="font-size: 10px">{{ $productsBySpeciesAndCaptureZone['species']->scientific_name.'('. $productsBySpeciesAndCaptureZone['species']->fao.')'.' - '.$productsBySpeciesAndCaptureZone['captureZone']->name }}</th>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                        </tr>
                    @endforeach
                    {{-- <tr class="border-b border-gray-200">
                        <th class="italic text-left p-1.5 font-normal">Octopus Vulgaris - FAO 27 – Atlantic, Northeast</th>
                        <td></td>
                        <td></td>
                    </tr> --}}
                    <tr class="border-b border-black">
                        <th class="text-left p-1.5 font-normal">Pallets: 56</th>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="text-left font-medium p-1.5">Total</th>
                        <td class="text-center">{{-- {{ $order->summary->total()->boxes }} --}}</td>
                        <td class="text-center">{{-- {{ number_format($order->summary->total()->netWeight, 2) }}  --}}kg</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="grid grid-cols-12 mt-8">
            <div class="col-span-10">
                <p style="font-size: 1.2rem;"><strong>Delivery Address:</strong></p>
                <p class="text-sm mt-3 preserve-line-breaks bold-first-line">
                    {!! nl2br($order->shipping_address) !!}
                </p>
                <p style="font-size: 1.2rem; margin-top: 3rem;"><strong>Terms & Conditions:</strong></p>
                <p class="mt-3 text-sm">
                    <strong class="mr-1">INCOTERM:</strong> DDP (delivered duty paid).
                </p>
            </div>
        </div>
    </div>
    
</body>
</html>

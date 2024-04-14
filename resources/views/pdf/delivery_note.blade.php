<<!DOCTYPE html>
<html>
<head>
    <title>Delivery Note</title>
    <!-- Estilos de Tailwind -->
    <style>
        body {
            font-family: 'DejaVu Sans';
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        .bar-blue {
            background-color: #06d6ff;
        }
        .bar-dark-blue {
            background-color: #079def;
        }
        .bar-gray {
            background-color: #d1d1d1;
        }
        .bar-black {
            background-color: black;
        }
        .col-span-4 {
            grid-column: span 4;
        }
        .col-span-8 {
            grid-column: span 8;
            line-height: 122%;
            text-align: right;
            color: #1E79BB;
        }
        .col-span-3 {
            grid-column: span 3;
            padding-right: 0;
            padding-left: 0;
            height: 0.1rem;
        }
        .col-span-5 {
            grid-column: span 5;
            margin-top: 1.2rem;
        }
        .col-span-7 {
            grid-column: span 7;
            text-align: right;
            margin-top: 2rem;
            line-height: 100%;
        }
        .w-full {
            width: 100%;
        }
        .italic {
            font-style: italic;
        }
        .text-left {
            text-align: left;
        }
        .text-sm {
            font-size: small;
        }
        .border-b {
            border-bottom: 1px solid #ccc;
        }
        .border-black {
            border: 1px solid black;
        }
        .font-medium {
            font-weight: 500;
        }
        .p-2 {
            padding: 8px;
        }
        .cliente {
            font-size: 0.9rem;
        }
        .mt-12 {
            margin-top: 3rem;
        }
        .bold-first-line {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="pt-4">
        <div class="grid grid-cols-12" style="margin-bottom: 1rem;">
            <div class="col-span-4">
                <img src="{{ asset('images/logos/brisamar-slogan.png') }}" alt="Logo">
            </div>
            <div class="col-span-8">
                <p>
                    <strong>CONGELADOS BRISAMAR S.L.</strong><br>
                    C.I.F.: B-215 732 82<br>
                    Poligono vista hermosa, nave 11A<br>
                    21410 Isla Cristina Huelva
                </p>
            </div>
        </div>
        <div class="grid grid-cols-12" style="margin-bottom: 1rem;">
            <div class="col-span-3 bar-blue"></div>
            <div class="col-span-3 bar-dark-blue"></div>
            <div class="col-span-3 bar-gray"></div>
            <div class="col-span-3 bar-black"></div>
        </div>
        <div class="grid grid-cols-12 mt-2 mb-4">
            <div class="col-span-12">
                <p><strong>DELIVERY NOTE</strong></p>
            </div>
            <div class="col-span-5 mt-3">
                <table class="w-full">
                    <tbody>
                        <tr class="border-b">
                            <th class="text-left font-medium text-sm p-2">Number</th>
                            <td class="text-left text-sm">{{ $order->id }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="text-left font-medium text-sm p-2">Date</th>
                            <td class="text-left text-sm">{{-- {{ $order->loadDate->format('m/d/Y') }} --}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-span-7 text-right">
                <p class="cliente bold-first-line">{{ $order->billingAddress }}</p>
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
                    <tr class="border-b">
                        <th class="italic text-left p-1.5 font-normal">Octopus Vulgaris - FAO 27 – Atlantic, Northeast</th>
                        <td></td>
                        <td></td>
                    </tr>
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
                <p><strong>Delivery Address:</strong></p>
                <p class="text-sm mt-3 bold-first-line">{{ $order->shippingAddress }}</p>
                <p style="font-size: 1.2rem;" class="mt-12"><strong>Terms & Conditions:</strong></p>
                <p class="text-sm mt-3">
                    <strong class="mr-1">INCOTERM:</strong> DDP (delivered duty paid).
                </p>
            </div>
        </div>
    </div>
</body>
</html>

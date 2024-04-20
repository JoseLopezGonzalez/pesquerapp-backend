<!DOCTYPE html>
<html>
<head>
    <title>Delivery Note</title>
    <style>
        body { font-family: 'DejaVu Sans'; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .preserve-line-breaks { white-space: pre-line; }
        .bold-first-line { font-weight: bold; }
    </style>
</head>
<body>
    <div style="padding-top: 1rem;">
        <div style="display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); margin-bottom: 1rem;">
            <div style="grid-column: span 4 / span 4;">
                <img src="https://congeladosbrisamar.es/logo2" alt="Logo" style="max-width: 50px;">
            </div>
            <div style="grid-column: span 8 / span 8; line-height: 122%; text-align: right; color: #1E79BB;">
                <p style="font-size: 10pt;">
                    <strong>CONGELADOS BRISAMAR S.L.</strong><br>
                    C.I.F.: B-215 732 82<br>
                    Poligono vista hermosa, nave 11A<br>
                    21410 Isla Cristina Huelva
                </p>
            </div>
        </div>
        <div style="display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); margin-bottom: 1rem;">
            <div style="grid-column: span 3 / span 3; padding-right: 0; padding-left: 0; height: 0.1rem; background-color: #06d6ff;"></div>
            <div style="grid-column: span 3 / span 3; padding-right: 0; padding-left: 0; height: 0.1rem; background-color: #079def;"></div>
            <div style="grid-column: span 3 / span 3; padding-right: 0; padding-left: 0; height: 0.1rem; background-color: #d1d1d1;"></div>
            <div style="grid-column: span 3 / span 3; padding-right: 0; padding-left: 0; height: 0.1rem; background-color: black;"></div>
        </div>
        <div style="display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); margin-top: 0.5rem; margin-bottom: 1rem;">
            <div style="grid-column: span 12 / span 12;">
                <p style="margin-top: 1.2rem; font-size: 1.5rem;"><strong>DELIVERY NOTE</strong></p>
            </div>
            <div style="grid-column: span 5 / span 5; margin-top: 0.75rem;">
                <table style="width: 100%;">
                    <tbody>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <th style="text-align: left; font-weight: bold; font-size: small; padding: 8px;">Number</th>
                            <td style="text-align: left; font-size: small; padding: 8px;">{{ $order->id }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <th style="text-align: left; font-weight: bold; font-size: small; padding: 8px;">Date</th>
                            <td style="text-align: left; font-size: small; padding: 8px;">{{ $order->load_date }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <th style="text-align: left; font-weight: bold; font-size: small; padding: 8px;">Buyer Reference</th>
                            <td style="text-align: left; font-size: small; padding: 8px;">{{ $order->buyer_reference }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="grid-column: span 7 / span 7; text-align: right; line-height: 100%; margin-top: 0.5rem;">
                <p class="cliente preserve-line-breaks bold-first-line" style="font-size: 0.9rem;">
                    {!! nl2br($order->billing_address) !!}
                </p>
            </div>
        </div>
        <div style="width: 100%; margin-top: 3rem;">
            <table style="width: 100%; font-size: small;">
                <thead style="border-bottom: 2px solid black;">
                    <tr>
                        <th style="text-align: left; padding: 6px;">Item</th>
                        <th style="text-align: center;">Boxes</th>
                        <th style="text-align: center;">Weight</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->productsBySpeciesAndCaptureZone as $productsBySpeciesAndCaptureZone)
                        @foreach($productsBySpeciesAndCaptureZone['products'] as $product)
                            <tr style="border-bottom: 1px solid #ccc;">
                                <th style="text-align: left; font-weight: bold; padding: 6px;">{{ $product['product']->article->name}}</th>
                                <td style="text-align: center;">{{ $product['boxes'] }}</td>
                                <td style="text-align: center;">{{ number_format($product['netWeight'], 2) }} kg</td>
                            </tr>
                        @endforeach
                        <tr style="border-bottom: 1px solid #ccc;">
                            <th style="text-align: left; padding: 6px;">{{ $productsBySpeciesAndCaptureZone['species']->scientific_name.'('. $productsBySpeciesAndCaptureZone['species']->fao.')'.' - '.$productsBySpeciesAndCaptureZone['captureZone']->name }}</th>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                    @endforeach
                    <tr style="border-bottom: 2px solid black;">
                        <th style="text-align: left; font-weight: normal; padding: 6px;">Pallets: 56</th>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th style="text-align: left; font-weight: bold; padding: 6px;">Total</th>
                        <td style="text-align: center;"></td>
                        <td style="text-align: center;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); margin-top: 2rem;">
            <div style="grid-column: span 10 / span 10;">
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


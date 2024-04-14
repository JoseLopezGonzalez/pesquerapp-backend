<!DOCTYPE html>
<html>
<head>
    <title>Delivery Note</title>
</head>
<body>
    <div style="padding-top: 1.6rem;">
        <div style="display: grid; grid-template-columns: repeat(12, 1fr); margin-bottom: 1rem;">
            <div style="grid-column: span 4;">
                <img src="{{ asset('images/logos/brisamar-slogan.png') }}" alt="Logo">
            </div>
            <div style="grid-column: span 8; line-height: 122%; text-align: right; color: #1E79BB;">
                <p style="font-size: 10pt;">
                    <strong>CONGELADOS BRISAMAR S.L.</strong><br>
                    C.I.F.: B-215 732 82<br>
                    Poligono vista hermosa, nave 11A<br>
                    21410 Isla Cristina Huelva
                </p>
            </div>
        </div>
        <div style="display: grid; grid-template-columns: repeat(12, 1fr); margin-bottom: 1rem;">
            <!-- Color bars across the top -->
            <div style="grid-column: span 3; padding-right: 0; padding-left: 0; height: 0.1rem; background-color: #06d6ff;"></div>
            <div style="grid-column: span 3; padding-right: 0; padding-left: 0; height: 0.1rem; background-color: #079def;"></div>
            <div style="grid-column: span 3; padding-right: 0; padding-left: 0; height: 0.1rem; background-color: #d1d1d1;"></div>
            <div style="grid-column: span 3; padding-right: 0; padding-left: 0; height: 0.1rem; background-color: black;"></div>
        </div>
        <div style="display: grid; grid-template-columns: repeat(12, 1fr); margin-top: 1.2rem; margin-bottom: 1rem;">
            <div style="grid-column: span 12;">
                <p style="font-size: 1.5rem;"><strong>DELIVERY NOTE</strong></p>
            </div>
            <div style="grid-column: span 5; margin-top: 1.2rem;">
                <table style="width: 100%;">
                    <tbody>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <th style="text-align: left; font-weight: 500; font-size: small; padding: 8px;">Number</th>
                            <td style="text-align: left; font-size: small;">{{ $order->id }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ccc;">
                            <th style="text-align: left; font-weight: 500; font-size: small; padding: 8px;">Date</th>
                            <td style="text-align: left; font-size: small;">{{-- {{ $order->loadDate->format('m/d/Y') }} --}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="grid-column: span 7; text-align: right; margin-top: 1.2rem; line-height: 100%;">
                <p style="font-size: 0.9rem;">{{ $order->billingAddress }}</p>
            </div>
        </div>
        <div style="width: 100%; margin-top: 1.2rem;">
            <table style="width: 100%; font-size: small;">
                <thead style="border-bottom: 2px solid black;">
                    <tr>
                        <th style="text-align: left; padding: 12px;">Item</th>
                        <th style="text-align: center;">Boxes</th>
                        <th style="text-align: center;">Weight</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #ccc;">
                        <th style="font-style: italic; text-align: left; padding: 12px;">Octopus Vulgaris - FAO 27 – Atlantic, Northeast</th>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr style="border-bottom: 1px solid black;">
                        <th style="text-align: left; padding: 12px;">Pallets: 56</th>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th style="text-align: left; font-weight: 500; padding: 12px;">Total</th>
                        <td style="text-align: center;">{{-- {{ $order->summary->total()->boxes }} --}}</td>
                        <td style="text-align: center;">{{-- {{ number_format($order->summary->total()->netWeight, 2) }}  --}}kg</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="display: grid; grid-template-columns: repeat(12, 1fr); margin-top: 2rem;">
            <div style="grid-column: span 10;">
                <p style="font-size: 1.2rem;"><strong>Delivery Address:</strong></p>
                <p style="font-size: small; margin-top: 1.2rem; font-weight: bold;">{{ $order->shippingAddress }}</p>
                <p style="font-size: 1.2rem; margin-top: 3rem;"><strong>Terms & Conditions:</strong></p>
                <p style="font-size: small; margin-top: 1.2rem;">
                    <strong style="margin-right: 1px;">INCOTERM:</strong> DDP (delivered duty paid).
                </p>
            </div>
        </div>
    </div>
</body>
</html>

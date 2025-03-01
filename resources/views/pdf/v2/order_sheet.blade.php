<!DOCTYPE html>
<html>

<head>
    <title>Hoja de Pedido</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: 'DejaVu Sans';
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ocupa el 100% de la altura */
            width: 100%;
            max-width: 210mm;
            background-color: white;
            padding: 20px;
        }

        .table-container {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container thead {
            position: sticky;
            top: 0;
            background-color: white;
        }

        .table-container td,
        .table-container th {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
        }

        .footer {
            margin-top: auto;
            text-align: center;
            font-size: 12px;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Encabezado -->
        <div class="mb-6">
            <div class="flex justify-between">
                <div>
                    <h1 class="text-lg font-bold">Congelados Brisamar S.L.</h1>
                    <p>C/Dieciocho de Julio de 1922 Nº2 - 21410 Isla Cristina</p>
                    <p>Tel: +34 613 09 14 94 - administracion@congeladosbrisamar.es</p>
                </div>
                <div>
                    <h2 class="text-lg font-bold">PEDIDO</h2>
                    <p class="font-medium"><span>{{ $order->formattedId }}</span></p>
                    <p class="font-medium">Fecha: 02/02/2025 <span>{{ $order->load_date }}</span></p>
                </div>
            </div>
        </div>

        <!-- Tabla Detalle de Productos -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Código GTIN</th>
                        <th>Lote</th>
                        <th>Cajas</th>
                        <th>Peso Neto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->productsWithLotsDetails as $productLine)
                        <tr>
                            <td>{{ $productLine['product']['article']['name'] }}</td>
                            <td>{{ $productLine['product']['boxGtin'] }}</td>
                            <td>{{ count($productLine['lots']) == 1 ? $productLine['lots'][0]['lot'] : '' }}</td>
                            <td>{{ $productLine['product']['boxes'] }}</td>
                            <td>{{ $productLine['product']['netWeight'] }} kg</td>
                        </tr>
                        @foreach ($productLine['lots'] as $lot)
                            @if (count($productLine['lots']) > 1)
                                <tr>
                                    <td></td>
                                    <td class="text-end">↪︎</td>
                                    <td>{{ $lot['lot'] }}</td>
                                    <td>{{ $lot['boxes'] }}</td>
                                    <td>{{ $lot['netWeight'] }} kg</td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td>{{ $order->totalBoxes }}</td>
                        <td>{{ $order->totalNetWeight }} kg</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Documento generado electrónicamente. No requiere firma.</p>
            <p>Ref: 2341434-231143</p>
        </div>
    </div>
</body>

</html>

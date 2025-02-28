<!-- resources/views/pdf/delivery_note.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Hoja de Pedido</title>
    {{-- Tailwind no funciona, lo cojo todo directamente de un cdn --}}

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'DejaVu Sans';
        }

        .bold-first-line::first-line {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="max-w-[210mm] mx-auto p-6 bg-white shadow-md rounded">
        <!-- Encabezado -->
        <div class="flex justify-between items-start mb-6">
            <div class="flex items-center gap-2">
                <div class="w-16 h-16 bg-slate-100 flex items-center justify-center rounded border">
                    <img src="/placeholder.svg?height=64&width=64" alt="Logo de la empresa"
                        class="object-contain w-16 h-16">
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800">MARISCOS DEL CANTÁBRICO S.L.</h1>
                    <p class="text-sm text-slate-600">C/ Muelle Pesquero, 24 - 39004 Santander</p>
                    <p class="text-sm text-slate-600">Tel: 942 123 456 - info@mariscos-cantabrico.es</p>
                </div>
            </div>

            <div class="text-right">
                <div class="bg-slate-100 p-3 rounded border">
                    <h2 class="text-lg font-bold text-slate-800">PEDIDO</h2>
                    <p class="text-sm font-medium">Nº: <span class="text-slate-800">{{ $order->formattedId }}</span></p>
                    <p class="text-sm font-medium">Fecha: <span class="text-slate-800">{{ $order->load_date }}</span></p>
                </div>
            </div>
        </div>

        <!-- Datos del cliente y transporte | Direcciones -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="space-y-4">
                <div class="border rounded p-4">
                    <h3 class="font-bold text-slate-800 mb-2">DATOS DEL CLIENTE</h3>
                    <div class="text-sm space-y-1">
                        <p><span class="font-medium">Nombre:</span> Distribuciones Marítimas S.A.</p>
                        <p><span class="font-medium">NIF/CIF:</span> B-12345678</p>
                        <p><span class="font-medium">Buyer Reference:</span> BUY-2025-0789</p>
                        <p class="font-medium mt-2">Correos electrónicos:</p>
                        <ul class="list-disc pl-5">
                            <li>pedidos@distribuciones-maritimas.com</li>
                            <li>logistica@distribuciones-maritimas.com</li>
                            <li>facturacion@distribuciones-maritimas.com</li>
                        </ul>
                    </div>
                </div>

                <div class="border rounded p-4">
                    <h3 class="font-bold text-slate-800 mb-2">DATOS DE TRANSPORTE</h3>
                    <div class="text-sm space-y-1">
                        <p><span class="font-medium">Empresa:</span> Transportes Rápidos del Norte S.L.</p>
                        <p><span class="font-medium">Email:</span> logistica@transportesrapidos.es</p>
                    </div>
                </div>
            </div>

            <div class="border rounded p-4">
                <h3 class="font-bold text-slate-800 mb-2">DIRECCIÓN DE FACTURACIÓN</h3>
                <p class="text-sm">Polígono Industrial La Marina, Nave 12</p>
                <p class="text-sm">48950 Erandio, Vizcaya</p>
                <p class="text-sm">España</p>

                <hr class="my-4 border-dashed border-slate-300">

                <h3 class="font-bold text-slate-800 mb-2">DIRECCIÓN DE ENVÍO</h3>
                <p class="text-sm">Mercado Central de Abastos</p>
                <p class="text-sm">Puesto 45-48</p>
                <p class="text-sm">48002 Bilbao, Vizcaya</p>
                <p class="text-sm">España</p>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="mb-6">
            <h3 class="font-bold text-slate-800 mb-2">DETALLE DE PRODUCTOS</h3>
            <div class="border rounded overflow-hidden">
                <table class="w-full border-collapse">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="border p-2 font-medium">Producto</th>
                            <th class="border p-2 font-medium">Código GTIN</th>
                            <th class="border p-2 font-medium">Lote</th>
                            <th class="border p-2 font-medium">Peso Neto (kg)</th>
                            <th class="border p-2 font-medium">Peso Bruto (kg)</th>
                        </tr>
                    </thead>
                    <!-- <tbody>
                        @foreach($productos as $producto)
                            <tr class="bg-white border-t">
                                <td class="border p-2">{{ $producto['nombre'] }}</td>
                                <td class="border p-2">{{ $producto['codigo'] }}</td>
                                <td class="border p-2">{{ $producto['lote'] }}</td>
                                <td class="border p-2">{{ number_format($producto['pesoNeto'], 2) }}</td>
                                <td class="border p-2">{{ number_format($producto['pesoBruto'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody> -->
                </table>
            </div>
        </div>

        <!-- Totales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="border rounded p-4 flex justify-between">
                <h3 class="font-bold text-slate-800">TOTAL PALETS:</h3>
               <!--  <span class="text-xl">{{ $totalPalets }}</span> -->
            </div>

            <div class="border rounded p-4 flex justify-between">
                <h3 class="font-bold text-slate-800">TOTAL CAJAS:</h3>
                <!-- <span class="text-xl">{{ $totalCajas }}</span> -->
            </div>

            <div class="border rounded p-4 flex justify-between bg-slate-50 border-2 border-slate-300">
                <h3 class="font-bold text-slate-800">PESO NETO TOTAL:</h3>
                <span class="text-xl font-bold">{{ number_format($order->netWeight, 2) }} kg</span>
            </div>
        </div>

        <!-- Pie de página -->
        <hr class="my-4 border-dashed border-slate-300">
        <div class="flex justify-between items-end">
            <div class="text-xs text-slate-500">
                <p>Documento generado electrónicamente. No requiere firma.</p>
                <!-- <p>Ref: {{ $numeroPedido }} - {{ $fecha }}</p> -->
            </div>

            <div class="flex flex-col items-center">
                <div class="w-24 h-24 border rounded flex items-center justify-center bg-white">
                    <img src="/qrcode-placeholder.png" alt="QR Code" class="w-20 h-20">
                </div>
                <p class="text-xs text-slate-500 mt-1">Escanea para ver el pedido online</p>
            </div>
        </div>
    </div>
</body>

</html>
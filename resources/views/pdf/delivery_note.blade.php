<!-- resources/views/pdf/delivery_note.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Nota de Entrega</title>
    <style>
        body { font-family: 'DejaVu Sans'; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h1>Nota de Entrega</h1>
    <p>Pedido Número: {{ $order->id }}</p>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
           {{--  @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                </tr>
            @endforeach --}}
        </tbody>
    </table>
</body>
</html>

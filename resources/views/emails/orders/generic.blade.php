<x-mail::message>

# {{ $order->customer->name }}

<br>

Adjunto le enviamos el documento **{{ $documentName }}** correspondiente al pedido número **{{ $order->formattedId }}**.

<br>

## Detalles del Pedido:
- Cliente: {{ $order->customer->name }}
- Número de Pedido: {{ $order->formattedId }}
- Fecha de Carga: {{ date('d/m/Y', strtotime($order->load_date)) }}

<br>

Si necesita más información, no dude en contactarnos.

Saludos cordiales

</x-mail::message>
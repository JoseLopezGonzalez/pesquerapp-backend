<x-mail::message>

# {{ $order->transport->name }}

## &nbsp;

## Detalles del Envío:

- **{{ $order->customer->alias }}**
- **Número de Pedido:** {{ $order->formattedId }}
- **Fecha de carga:** {{ date('d/m/Y', strtotime($order->load_date)) }}
- **Destino:** {!! nl2br(e($order->shipping_address)) !!}

## &nbsp;

<x-mail::table>
    | Nº Palet | Cajas | Peso Total |
    |:-----------:|:-----------:|:------------:|
    @foreach ($order->pallets as $pallet)
        | #{{ str_pad($pallet->id, 9, ' ', STR_PAD_RIGHT) }} |
        {{ str_pad($pallet->numberOfBoxes, 10, ' ', STR_PAD_RIGHT) }} |
        {{ str_pad(number_format($pallet->netWeight, 2, ',', '.') . ' kg', 10, ' ', STR_PAD_RIGHT) }} |
    @endforeach
</x-mail::table>

## &nbsp;

## Documentación Adjunta:

<x-mail::panel>
    Se adjunta la documentación relevante necesaria para la manipulación y transporte de las mercancías.
</x-mail::panel>

## &nbsp;

## Observaciones:

Por favor, revisen los documentos adjuntos para aseguraros que todos los detalles son correctos y que tienen todo lo necesario.

*Si encuentran alguna discrepancia o necesitan más información, no duden en contactarnos a [{{ config('company.contact.email_orders') }}](mailto:{{ config('company.contact.email_orders') }}) ({{ config('company.contact.phone_orders') }})*

Saludos.
</x-mail::message>
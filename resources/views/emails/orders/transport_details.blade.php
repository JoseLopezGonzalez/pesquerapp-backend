<x-mail::message>


# {{ $order->transport->name }}


## Detalles del Envío:

- **{{ $order->customer->alias }}**
- **Número de Pedido:** {{ $order->formattedId }}
- **Fecha de carga:** {{ date('d/m/Y', strtotime($order->load_date)) }}
- **Destino:** {!! nl2br(e($order->shipping_address)) !!}


| Nº Palet | Cajas | Peso Total |
|----------|-------|------------|
{{-- Bucle por $order->pallets --}}
@foreach ($order->pallets as $pallet)
| #{{ $pallet->id }} | {{ $pallet->numberOfBoxes }} | {{ number_format($pallet->netWeight, 2, ',', '.') }}kg |
@endforeach
{{-- Fin del bucle por $order->pallets --}}

## Palets:

@foreach ($order->pallets as $pallet)
**Palet #{{ $pallet->id }}**
- **Cajas:** {{ $pallet->numberOfBoxes }}
- **Peso Neto:** {{ number_format($pallet->netWeight, 2, ',', '.') }}kg
@endforeach




| Laravel       | Table         | Example  |
| ------------- | ------------- | -------- |
| Col 2 is      | Centered      | $10      |
| Col 3 is      | Right-Aligned | $20      |


## Documentación Adjunta:

<x-mail::panel>
    Se adjunta la documentación relevante necesaria para la manipulación y transporte de las mercancías.
</x-mail::panel>

## Observaciones:

Por favor, revisen los documentos adjuntos para aseguraros que todos los detalles son correctos y que tienen todo lo necesario.


*Si encuentran alguna discrepancia o necesitan más información, no duden en contactarnos a pedidos@congeladosbrisamar.es (+34 613 091 494) .*


Saludos.
</x-mail::message>
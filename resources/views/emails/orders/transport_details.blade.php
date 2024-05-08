<x-mail::message>

# {{ $order->transport->name }}

<br>

**ES -** Puede encontrar en archivo adjunto la documentación del pedido **{{$order->formattedId}}** cargado el día {{ $order->load_date }}.


<br>


Saludos.
</x-mail::message>
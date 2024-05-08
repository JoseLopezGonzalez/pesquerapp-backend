<x-mail::message>


# {{ $order->transport->name }}


## Detalles del Envío:

- **Número de Pedido:** {{ $order->formattedId }}
- **Fecha de carga:** {{ strtotime($order->load_date) }}
- **Destino:** {!! nl2br(e($order->shipping_address)) !!}

## Documentación Adjunta:

<x-mail::panel>
    Se adjunta la documentación relevante necesaria para la manipulación y transporte de las mercancías.
</x-mail::panel>

<br>
Por favor, revise los documentos adjuntos para asegurarse de que todos los detalles son correctos y que tiene todo lo necesario para un proceso de transporte sin inconvenientes.

<br>
*Si encuentra alguna discrepancia o si necesita más información, no dude en contactarnos al +34 620 714 139.*


Saludos.
</x-mail::message>
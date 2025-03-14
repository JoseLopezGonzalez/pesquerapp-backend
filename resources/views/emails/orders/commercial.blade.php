<x-mail::message>

    # Pedido {{ $order->formattedId }} - {{ $order->customer->name }}

    <br>

    Estimado/a equipo comercial,

    Adjuntamos los documentos correspondientes al pedido **#{{ $order->formattedId }}** de
    **{{ $order->customer->name }}**, con fecha de carga **{{ date('d/m/Y', strtotime($order->load_date)) }}**.

    <br>

    Documentos incluidos:
    - Nota de Carga
    - Packing List

    <br>

    Si necesitan más información, contacten con el equipo de operaciones.

    Saludos cordiales,
    **Departamento de Operaciones**

</x-mail::message>
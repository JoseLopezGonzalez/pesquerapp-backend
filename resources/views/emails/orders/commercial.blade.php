<x-mail::message>

# {{ $order->customer->name }}

<br>

**Pedido Nº:** **{{ $order->formattedId }}**
**Fecha de Carga:** {{ date('d/m/Y', strtotime($order->load_date)) }}

<br>

Estimado/a equipo comercial,

Adjuntamos los documentos correspondientes al pedido **#{{ $order->formattedId }}** de
**{{ $order->customer->name }}**.

<br>

**Documentos incluidos:**
- Nota de Carga
- Packing List

<br>

Por favor, revisen la documentación adjunta para asegurarse de que toda la información esté correcta.

<br>

Si necesitan más información, pueden contactar directamente con el equipo de operaciones a [comercial@empresa.com](mailto:comercial@empresa.com).

<br>

Saludos cordiales,
**Departamento de Operaciones**

</x-mail::message>
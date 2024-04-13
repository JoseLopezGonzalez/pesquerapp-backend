<x-mail::message>
# Hola, {{ $customer_name }}

Tu pedido #{{ $order_details->id }} ha sido enviado.

<x-mail::button :url="''">
Button Texte
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

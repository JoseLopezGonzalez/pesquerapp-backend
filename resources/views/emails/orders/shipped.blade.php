<x-mail::message>
# Hola, {{ $customer_name }}

Tu pedido ha sido enviado.

<x-mail::button :url="''">
Button Texte
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

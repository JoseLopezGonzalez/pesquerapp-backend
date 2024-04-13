<x-mail::message>

# {{ $customer_name }}

<br>

**ES -** Su pedido con número **#{{$order_id}}** ha sido enviado. En breve recibirá su factura.



**IT -** Il suo ordine con il numero **#{{$order_id}}** è stato spedito. Riceverà presto la sua fattura.



**EN -** Your order with number **#{{$order_id}}** has been shipped. You will receive your invoice shortly.



**FR -** Votre commande avec le numéro **#{{$order_id}}** a été expédiée. Vous recevrez bientôt votre facture.



**PT -** Seu pedido com o número **#{{$order_id}}** foi enviado. Você receberá sua fatura em breve.

<br>

<x-mail::panel>
This is the panel content.
</x-mail::panel>

<x-mail::table>
| Laravel       | Table         | Example  |
| ------------- |:-------------:| --------:|
| Col 2 is      | Centered      | $10      |
| Col 3 is      | Right-Aligned | $20      |
</x-mail::table>


<x-mail::button :url="''">
Delivery Note
</x-mail::button>

Saludos / Saluti / Best regards / Cordialement / Atenciosamente.
</x-mail::message>
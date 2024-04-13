<x-mail::message>

# {{ $customer_name }}

<br>

**ES -** Su pedido con número **#{{$order_id}}** ha sido enviado. En breve recibirá su factura.



**IT -** Il suo ordine con il numero **#{{$order_id}}** è stato spedito. Riceverà presto la sua fattura.



**EN -** Your order with number **#{{$order_id}}** has been shipped. You will receive your invoice shortly.



**FR -** Votre commande avec le numéro **#{{$order_id}}** a été expédiée. Vous recevrez bientôt votre facture.



**PT -** Seu pedido com o número **#{{$order_id}}** foi enviado. Você receberá sua fatura em breve.


<x-mail::button :url="''">
Delivery Note
</x-mail::button>

Saludos / Saluti / Best regards / Cordialement / Atenciosamente.
</x-mail::message>
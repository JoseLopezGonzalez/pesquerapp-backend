<x-mail::message>

# {{ $order->customer->name }}

<br>

**ES -** Su pedido con número **#{{$order->formattedId}}** ha sido enviado. En breve recibirá su factura.



**IT -** Il suo ordine con il numero **#{{$order->formattedId}}** è stato spedito. Riceverà presto la sua fattura.



**EN -** Your order with number **#{{$order->formattedId}}** has been shipped. You will receive your invoice shortly.



**FR -** Votre commande avec le numéro **#{{$order->formattedId}}** a été expédiée. Vous recevrez bientôt votre facture.



**PT -** Seu pedido com o número **#{{$order->formattedId}}** foi enviado. Você receberá sua fatura em breve.

<br>



Saludos / Saluti / Best regards / Cordialement / Atenciosamente.
</x-mail::message>
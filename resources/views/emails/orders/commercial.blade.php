<!-- <x-mail::message>

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

    Si necesitan más información, pueden contactar directamente con el equipo de operaciones a
    [comercial@empresa.com](mailto:comercial@empresa.com).

    <br>

    Saludos cordiales,
    **Departamento de Operaciones**

</x-mail::message> -->

<x-mail::message>

    # {{ $order->customer->name }}

    <br>

    **ES -** Su pedido con número **{{$order->formattedId}}** ha sido enviado. En breve recibirá su factura.



    **IT -** Il suo ordine con il numero **{{$order->formattedId}}** è stato spedito. Riceverà presto la sua fattura.



    **EN -** Your order with number **{{$order->formattedId}}** has been shipped. You will receive your invoice shortly.



    **FR -** Votre commande avec le numéro **{{$order->formattedId}}** a été expédiée. Vous recevrez bientôt votre
    facture.



    **PT -** Seu pedido com o número **{{$order->formattedId}}** foi enviado. Você receberá sua fatura em breve.

    <br>



    Saludos / Saluti / Best regards / Cordialement / Atenciosamente.
</x-mail::message>
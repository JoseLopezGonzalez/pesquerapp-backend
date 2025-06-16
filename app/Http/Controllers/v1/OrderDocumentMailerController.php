<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Mail\OrderShipped;
use App\Mail\TransportShipmentDetails;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderDocumentMailerController extends Controller
{
    /**
     * Send documentation for a specific order.
     *
     * @param  int  $orderId
     * @return \Illuminate\Http\JsonResponse
     */

    public function sendDocumentation($orderId)
    {

        $order = Order::findOrFail($orderId); // Asegúrate de que el pedido existe
        Mail::to($order->emailsArray)
            ->cc($order->ccEmailsArray)  // Ejemplo de añadir un CC
            ->bcc('orders@brisatlantic.com')  // Ejemplo de añadir un BCC
            ->send(new OrderShipped($order)); // Envía el correo con la documentación


        return response()->json(['message' => 'Documentation sent successfully!']);
    }


    public function sendDocumentationTransport($orderId)
    {

        $order = Order::findOrFail($orderId); // Asegúrate de que el pedido existe
        Mail::to($order->transport->emailsArray)
            ->cc($order->transport->ccEmailsArray)  // Ejemplo de añadir un CC
            ->bcc(config('company.bcc_email'))  // Utiliza la configuración de BCC de la empresa
            ->send(new TransportShipmentDetails($order)); // Envía el correo con la documentación


        return response()->json(['message' => 'Documentation sent successfully!']);
    }

    //
}

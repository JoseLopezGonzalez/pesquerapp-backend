<?php

namespace App\Http\Controllers;

use App\Mail\OrderShipped;
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
        Mail::to($order->emails)->send(new OrderShipped($order)); // Envía el correo con la documentación

        return response()->json(['message' => 'Documentation sent successfully!']);
    }

    //
}

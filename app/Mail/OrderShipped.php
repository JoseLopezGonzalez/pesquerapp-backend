<?php

namespace App\Mail;

use Barryvdh\Snappy\Facades\SnappyPdf as PDF;



use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @param mixed $order Los datos del pedido.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $pdf = PDF::loadView('pdf.delivery_note', ['order' => $this->order])->output();

        return $this->subject('Order Shipped: #' . $this->order->id)
            ->markdown('emails.orders.shipped', [
                'customer_name' => $this->order->customer->name,
                'order_id' => $this->order->id,
                'order' => $this->order,
            ])
            ->attachData($pdf, 'delivery-note-' . $this->order->id . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}

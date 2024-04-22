<?php

namespace App\Mail;

use PDF; // Al principio de tu archivo PHP donde necesitas usar DomPDF


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Spatie\Browsershot\Browsershot; // Importa Browsershot


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

        
            // Usar Browsershot para generar el PDF
            $html = view('pdf.delivery_note', ['order' => $this->order])->render();
            $pdf = Browsershot::html($html)
                ->format('A4')
                ->showBackground()
                ->margins(10, 10, 10, 10)
                ->pdf();
    
            // Guarda temporalmente el PDF para adjuntarlo
            $pdfPath = storage_path('app/public/delivery-note-' . $this->order->id . '.pdf');
            file_put_contents($pdfPath, $pdf);
    
            return $this->subject('Order Shipped: #' . $this->order->id)
                        ->markdown('emails.orders.shipped', [
                            'order' => $this->order,
                        ])
                        ->attach($pdfPath, [
                            'as' => 'delivery-note-' . $this->order->id . '.pdf',
                            'mime' => 'application/pdf',
                        ]);
        
        
       /*  $pdf = PDF::loadView('pdf.delivery_note', ['order' => $this->order])->output();

        return $this->subject('Order Shipped: #' . $this->order->id)
                    ->markdown('emails.orders.shipped', [
                        'customer_name' => $this->order->customer->name,
                        'order_id' => $this->order->id,
                        'order' => $this->order,
                    ])
                    ->attachData($pdf, 'delivery-note-' . $this->order->id . '.pdf', [
                        'mime' => 'application/pdf',
                    ]); */
    }
}

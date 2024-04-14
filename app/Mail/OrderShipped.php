<?php

namespace App\Mail;

use Barryvdh\DomPDF\PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Shipped: #' . $this->order->id
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.shipped',
            with: [
                'customer_name' => $this->order->customer->name,
                'order_id' => $this->order->id
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Generar el PDF usando la vista delivery_note.blade.php
        $pdf = PDF::loadView('pdf.delivery_note', ['order' => $this->order])->output();

        // Adjuntar el PDF al correo electrónico
        return [
            new \Illuminate\Mail\Mailables\Attachment(
                data: $pdf,
                name: 'delivery-note-' . $this->order->id . '.pdf',
                contentType: 'application/pdf'
            )
        ];
    }
}

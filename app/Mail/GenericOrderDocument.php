<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericOrderDocument extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $view;
    public $subjectText;
    public $documentName; // ✅ Añadimos esto

    public function __construct($order, $view, $subjectText, $documentName) // ✅ Añadimos $documentName
    {
        $this->order = $order;
        $this->view = $view;
        $this->subjectText = $subjectText;
        $this->documentName = $documentName;
    }

    public function build()
    {
        return $this->subject($this->subjectText)
                    ->markdown($this->view, [
                        'order' => $this->order,
                        'documentName' => $this->documentName // ✅ Pasamos esto a la vista
                    ]);
    }
}

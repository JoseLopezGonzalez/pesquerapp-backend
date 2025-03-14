<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StandardOrderDocuments extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $subjectText;
    public $markdownTemplate; // Cambia según destinatario
    public $documents;

    public function __construct($order, $subjectText, $markdownTemplate, $documents)
    {
        $this->order = $order;
        $this->subjectText = $subjectText;
        $this->markdownTemplate = $markdownTemplate;
        $this->documents = $documents;
    }

    public function build()
    {
        $email = $this->subject($this->subjectText)
                      ->markdown($this->markdownTemplate, [
                          'order' => $this->order
                      ]);

        foreach ($this->documents as $document) {
            $email->attach($document['path'], [
                'as' => $document['name'],
                'mime' => 'application/pdf',
            ]);
        }

        return $email;
    }
}

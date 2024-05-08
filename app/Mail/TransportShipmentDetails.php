<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Beganovich\Snappdf\Snappdf;

class TransportShipmentDetails extends Mailable
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

    public function build()
    {

        $snappdf = new Snappdf();
        $html = view('pdf.delivery_note', ['order' => $this->order])->render();
        $snappdf->setChromiumPath('/usr/bin/google-chrome'); // Configura el camino correcto a Chrome

        // Personaliza tu PDF con argumentos y opciones
        $snappdf->addChromiumArguments('--no-sandbox');
        // Añadir más argumentos según necesidad

        /* NUEVO */

        $snappdf->setChromiumPath('/usr/bin/google-chrome'); // Asegúrate de cambiar esto por tu ruta específica

        /* Personalizando el PDF */
        $snappdf->addChromiumArguments('--margin-top=10mm');
        $snappdf->addChromiumArguments('--margin-right=30mm');
        $snappdf->addChromiumArguments('--margin-bottom=10mm');
        $snappdf->addChromiumArguments('--margin-left=10mm');


        // Agrega argumentos de Chromium uno por uno
        // Configuración para que el servidor no de errores y pueda trabajar bien con el PDF
        $snappdf->addChromiumArguments('--no-sandbox');
        $snappdf->addChromiumArguments('disable-gpu');
        $snappdf->addChromiumArguments('disable-translate');
        $snappdf->addChromiumArguments('disable-extensions');
        $snappdf->addChromiumArguments('disable-sync');
        $snappdf->addChromiumArguments('disable-background-networking');
        $snappdf->addChromiumArguments('disable-software-rasterizer');
        $snappdf->addChromiumArguments('disable-default-apps');
        $snappdf->addChromiumArguments('disable-dev-shm-usage');
        $snappdf->addChromiumArguments('safebrowsing-disable-auto-update');
        $snappdf->addChromiumArguments('run-all-compositor-stages-before-draw');
        $snappdf->addChromiumArguments('no-first-run');
        $snappdf->addChromiumArguments('no-margins');
        $snappdf->addChromiumArguments('print-to-pdf-no-header');
        $snappdf->addChromiumArguments('no-pdf-header-footer');
        $snappdf->addChromiumArguments('hide-scrollbars');
        $snappdf->addChromiumArguments('ignore-certificate-errors');

        $pdfContent = $snappdf->setHtml($html)
        ->generate();

        /* NUEVO */



        /* $pdfContent = $snappdf->generate(); */

        // Guarda temporalmente el PDF para adjuntarlo
        $pdfPath = storage_path('app/public/delivery-note-' . $this->order->id . '.pdf');
        file_put_contents($pdfPath, $pdfContent);

        return $this->subject('Detalle mercancía ' . /* formated date */ date('d/m/Y', strtotime($this->order->load_date)) . ' - ' . $this->order->customer->name)
            ->markdown('emails.orders.transport_details', [
                'order' => $this->order,
            ])
            ->attach($pdfPath, [
                'as' => 'Delivery-note-' . $this->order->formattedId . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
   
}

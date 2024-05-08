<?php

namespace App\Mail;

use PDF; // Al principio de tu archivo PHP donde necesitas usar DomPDF


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Beganovich\Snappdf\Snappdf;



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


        /* // Usar Browsershot para generar el PDF
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
         */

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

        return $this->subject('Order Shipped: #' . $this->order->id)
            ->markdown('emails.orders.shipped', [
                'order' => $this->order,
            ])
            ->attach($pdfPath, [
                'as' => 'Delivery-note-' . $this->order->formattedId . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}

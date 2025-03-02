<?php


namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\RawMaterialReceptionProductResource;
use App\Models\CeboDispatch;
use App\Models\Order;
use App\Models\RawMaterialReception;
use Beganovich\Snappdf\Snappdf;
use Illuminate\Http\Request;

/* 
use Illuminate\Support\Facades\Log;
use PDF; 
use Spatie\Browsershot\Browsershot; 
use Spatie\LaravelPdf\Facades\Pdf; 
*/

class PDFController extends Controller
{
    /**
     * Generate a delivery note PDF for a specific order.
     *
     * @param int $orderId
     * @return \Illuminate\Http\Response
     */
   

    public function generateRestrictedDeliveryNote($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $snappdf = new Snappdf();
        $html = view('pdf.restricted_delivery_note', ['order' => $order])->render();
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

        $pdf = $snappdf->setHtml($html)
            ->generate();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'Restricted_Delivery_note_' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    

    public function generateOrderCMR($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $snappdf = new Snappdf();
        $html = view('pdf.CMR', ['order' => $order])->render();
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

        $pdf = $snappdf->setHtml($html)
            ->generate();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'CMR_' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    /* Generar pdf con todas las RawMaterialReceptions */
    public function generateRawMaterialReceptionsDocument(Request $request)
    {
        // Reutiliza la lógica de filtro del método index
        $query = RawMaterialReception::with('supplier', 'products.product');

        $query->when($request->filled('id'), function ($query) use ($request) {
            $query->where('id', $request->id);
        });

        $query->when($request->filled('suppliers'), function ($query) use ($request) {
            $query->whereIn('supplier_id', $request->suppliers);
        });

        $query->when($request->filled('dates'), function ($query) use ($request) {
            $query->whereBetween('date', [$request->dates['start'], $request->dates['end']]);
        });

        $query->when($request->filled('species'), function ($query) use ($request) {
            $query->whereHas('products.product', function ($query) use ($request) {
                $query->whereIn('species_id', $request->species);
            });
        });

        $query->when($request->filled('products'), function ($query) use ($request) {
            $query->whereHas('products.product', function ($query) use ($request) {
                $query->whereIn('id', $request->products);
            });
        });

        $query->when($request->filled('notes'), function ($query) use ($request) {
            $query->where('notes', 'like', '%' . $request->notes . '%');
        });

        $rawMaterialReceptions = $query->get();

        if ($rawMaterialReceptions->isEmpty()) {
            return response()->json(['message' => 'No se encontraron recepciones de materia prima con los filtros proporcionados.'], 404);
        }



        $snappdf = new Snappdf();
        $html = view('pdf.rawMaterialReceptions.document', ['rawMaterialReceptions' => $rawMaterialReceptions])->render();



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

        $pdf = $snappdf->setHtml($html)
            ->generate();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'Recepciones_materia_prima_filtradas.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function generateCeboDispatchesDocument(Request $request)
    {
        // Reutiliza la lógica de filtro del método index para CeboDispatch
        $query = CeboDispatch::with('supplier', 'products.product');

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('suppliers')) {
            $query->whereIn('supplier_id', $request->suppliers);
        }

        if ($request->has('dates')) {
            $query->whereBetween('date', [$request->dates['start'], $request->dates['end']]);
        }

        if ($request->has('products')) {
            $query->whereHas('products.product', function ($query) use ($request) {
                $query->whereIn('id', $request->products);
            });
        }

        if ($request->has('notes')) {
            $query->where('notes', 'like', '%' . $request->notes . '%');
        }

        $ceboDispatches = $query->get();

        if ($ceboDispatches->isEmpty()) {
            return response()->json(['message' => 'No se encontraron despachos de cebo con los filtros proporcionados.'], 404);
        }

        $snappdf = new Snappdf();
        $html = view('pdf.ceboDispatches.document', ['ceboDispatches' => $ceboDispatches])->render();

        $snappdf->setChromiumPath('/usr/bin/google-chrome'); // Ajusta esta ruta según tu sistema

        // Genera el PDF
        $pdf = $snappdf->setHtml($html)->generate();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'Salidas_cebo_filtradas.pdf', ['Content-Type' => 'application/pdf']);
    }


    /* La pesca Orders */
    public function generateOrderCMRPesca($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $snappdf = new Snappdf();
        $html = view('pdf.CMR_pesca', ['order' => $order])->render();
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

        $pdf = $snappdf->setHtml($html)
            ->generate();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'CMR_Pesca_' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function generateDeliveryNotePesca($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $snappdf = new Snappdf();
        $html = view('pdf.delivery_note_pesca', ['order' => $order])->render();
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

        $pdf = $snappdf->setHtml($html)
            ->generate();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'Delivery_note_pesca' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function generateRestrictedDeliveryNotePesca($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $snappdf = new Snappdf();
        $html = view('pdf.restricted_delivery_note_pesca', ['order' => $order])->render();
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

        $pdf = $snappdf->setHtml($html)
            ->generate();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'Restricted_Delivery_note_pesca_' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function generateOrderSignsPesca($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $snappdf = new Snappdf();
        $html = view('pdf.order_signs_pesca', ['order' => $order])->render();
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

        $pdf = $snappdf->setHtml($html)
            ->generate();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'Order_sings_pesca_' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }


    /* v2 */
    
    public function generateOrderSheet($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $snappdf = new Snappdf();
        $html = view('pdf.v2.orders.order_sheet', ['order' => $order])->render();
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

        $pdf = $snappdf->setHtml($html)
            ->generate();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'Hoja_de_pedido_' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function generateOrderSigns($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $snappdf = new Snappdf();
        $html = view('pdf.v2.orders.order_signs', ['order' => $order])->render();
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

        $pdf = $snappdf->setHtml($html)
            ->generate();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'Letreros_transporte_' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function generateOrderPackingList($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $snappdf = new Snappdf();
        $html = view('pdf.v2.orders.order_packing_list', ['order' => $order])->render();
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

        $pdf = $snappdf->setHtml($html)
            ->generate();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'Packing_list_' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function generateLoadingNote($orderId)
    {
        $order = Order::findOrFail($orderId); // Asegúrate de cargar el pedido correctamente

        $snappdf = new Snappdf();
        $html = view('pdf.v2.orders.loading_note', ['order' => $order])->render();
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

        $pdf = $snappdf->setHtml($html)
            ->generate();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'Nota_de_carga_' . $order->formattedId . '.pdf', ['Content-Type' => 'application/pdf']);
    }
}

<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderMailerService;
use Illuminate\Http\Request;

class OrderDocumentController extends Controller
{
    protected $mailerService;

    public function __construct(OrderMailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    // Personalizado
    public function sendCustomDocumentation(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $request->validate([
            'documents' => 'required|array',
            'documents.*.type' => 'required|string',
            'documents.*.recipients' => 'required|array',
        ]);

        $this->mailerService->sendDocuments($order, $request->documents);

        return response()->json(['message' => 'Custom documentation sent successfully!']);
    }

    // Estándar
    public function sendStandardDocumentation($orderId)
    {
        $order = Order::findOrFail($orderId);
        $this->mailerService->sendStandardDocuments($order);

        return response()->json(['message' => 'Standard documentation sent successfully!']);
    }
}

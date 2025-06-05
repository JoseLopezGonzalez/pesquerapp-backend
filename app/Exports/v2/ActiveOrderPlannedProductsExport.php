<?php

namespace App\Exports\v2;

use App\Models\OrderPlannedProductDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class ActiveOrderPlannedProductsExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    public function collection()
    {
        return OrderPlannedProductDetail::with(['order.customer', 'product', 'tax'])
            ->whereHas('order', function ($q) {
                $q->where('status', 'pending')
                    ->orWhereDate('load_date', '>=', now());
            })
            ->get();
    }

    public function map($detail): array
    {
        return [
            $detail->order->id,
            $detail->order->customer->name ?? 'N/A',
            optional($detail->order->load_date)->format('Y-m-d'),
            $detail->product->name ?? 'N/A',
            $detail->quantity,
            $detail->boxes,
            number_format($detail->unit_price, 2, ',', '.'),
            $detail->tax->name ?? 'N/A',
        ];
    }

    public function headings(): array
    {
        return [
            'Pedido',
            'Cliente',
            'Fecha de carga',
            'Producto',
            'Cantidad prevista',
            'Cajas previstas',
            'Precio unitario',
            'Impuesto',
        ];
    }
}

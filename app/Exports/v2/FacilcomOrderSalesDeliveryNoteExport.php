<?php

namespace App\Exports\v2;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;

class FacilcomOrderSalesDeliveryNoteExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    use Exportable;

    protected $order;
    protected $index = 1;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function collection()
    {
        return collect([$this->order]); // Necesario para que se procese en `map()`
    }

    public function headings(): array
    {
        return [
            'CODIGO',
            'Fecha',
            'CODIGO CLIENTE',
            'Destino',
            'Cod. Producto',
            'Producto',
            'Cantidad Kg',
            'Precio',
            'Lote asignado',
        ];
    }

    public function map($order): array
    {
        $mappedRows = [];

        foreach ($order->productDetails as $product) {
            $mappedRows[] = [
                $this->index++,
                date('d/m/Y', strtotime($order->load_date)),
                optional($order->customer)->facilcom_code,
                optional($order->customer)->name,
                optional($product->product)->facilcom_code,
                optional($product->product)->name,
                $product->netWeight,
                $product->unitPrice,
                date('dmY', strtotime($order->load_date)),
            ];
        }

        return $mappedRows;
    }

    public function title(): string
    {
        return 'ALBARAN_FACILCOM';
    }
}

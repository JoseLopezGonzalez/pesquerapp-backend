<?php

namespace App\Exports\v2;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;

class FacilcomOrderSalesDeliveryNoteExport implements FromArray, WithHeadings, WithTitle
{
    use Exportable;

    protected $order;
    protected $index = 1;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->order->productDetails as $productDetail) {
            $rows[] = [
                $this->index,
                date('d/m/Y', strtotime($this->order->load_date)),
                $this->order->customer['facilcom_code'] ?? '',
                $this->order->customer['name'] ?? '',
                $productDetail['product']['facilcomCode'] ?? '',
                $productDetail['product']['name'] ?? '',
                $productDetail['netWeight'],
                $productDetail['unitPrice'],
                date('dmY', strtotime($this->order->load_date)),
            ];
        }

        // Línea adicional tipo "PEDIDO #123"
        $rows[] = [
            $this->index,
            date('d/m/Y', strtotime($this->order->load_date)),
            $this->order->customer['facilcom_code'] ?? '',
            $this->order->customer['name'] ?? '',
            '106',
            'PEDIDO #' . $this->order->id,
            '0',
            '0',
            '-',
        ];

        $this->index++;

        return $rows;
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

    public function title(): string
    {
        return 'ALBARAN_FACILCOM';
    }
}

<?php

namespace App\Exports\v2;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;


class FacilcomOrdersSalesDeliveryNotesExport implements FromArray, WithHeadings
{
    use Exportable;

    protected $orders;
    protected $index = 1;

    public function __construct(Collection $orders)
    {
        $this->orders = $orders;
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->orders as $order) {
            foreach ($order->productDetails as $productDetail) {
                $rows[] = [
                    $this->index,
                    date('d/m/Y', strtotime($order->load_date)),
                    $order->customer['facilcom_code'] ?? '',
                    $order->customer['name'] ?? '',
                    $productDetail['product']['facilcomCode'] ?? '',
                    $productDetail['product']['name'] ?? '',
                    $productDetail['netWeight'],
                    $productDetail['unitPrice'],
                    date('dmY', strtotime($order->load_date)),
                ];
            }

            // Línea resumen "PEDIDO #"
            $rows[] = [
                $this->index,
                date('d/m/Y', strtotime($order->load_date)),
                $order->customer['facilcom_code'] ?? '',
                $order->customer['name'] ?? '',
                '106',
                'PEDIDO #' . $order->id,
                '0',
                '0',
                '-',
            ];

            $this->index++;
        }

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
}

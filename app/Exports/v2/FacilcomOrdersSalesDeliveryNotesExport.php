<?php

namespace App\Exports\v2;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class FacilcomOrdersSalesDeliveryNotesExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    protected $orders;
    protected $index = 1;

    public function __construct(Collection $orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders;
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

        foreach ($order->productDetails as $productDetail) {
            $mappedRows[] = [
                'CODIGO' => $this->index++,
                'Fecha' => date('d/m/Y', strtotime($order->load_date)),
                'CODIGO CLIENTE' => $order->customer['facilcom_code'] ?? '',
                'Destino' => $order->customer['name'] ?? '',
                'Cod. Producto' => $productDetail['product']['facilcom_code'] ?? '',
                'Producto' => $productDetail['product']['name'] ?? '',
                'Cantidad Kg' => $productDetail['netWeight'],
                'Precio' => $productDetail['unitPrice'],
                'Lote asignado' => date('dmY', strtotime($order->load_date)),
            ];
        }

        return $mappedRows;
    }
}

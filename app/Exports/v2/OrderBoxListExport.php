<?php

namespace App\Exports\v2;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class OrderBoxListExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function collection()
    {
        $rows = [];

        foreach ($this->order->pallets as $pallet) {
            foreach ($pallet->boxes as $box) {
                $rows[] = [
                    'Pedido' => $this->order->id,
                    'Cliente' => $this->order->customer->name,
                    'Palet ID' => $pallet->id,
                    'Código de Caja' => $box->code, 
                    'Producto' => $box->product->article->name ?? 'N/A',
                    'GTIN Caja' => $box->product->boxGtin ?? 'N/A',
                    'Peso Neto' => number_format($box->netWeight, 2, ',', '.'),
                ];
            }
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'Pedido',
            'Cliente',
            'Palet ID',
            'Código de Caja',
            'Producto',
            'GTIN Caja',
            'Peso Neto',
        ];
    }

    public function map($row): array
    {
        return [
            $row['Pedido'],
            $row['Cliente'],
            $row['Palet ID'],
            $row['Código de Caja'],
            $row['Producto'],
            $row['GTIN Caja'],
            $row['Peso Neto'],
        ];
    }
}

<?php

namespace App\Exports\v2;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;

class OrderSalesDeliveryNoteExport implements FromCollection, WithHeadings, WithMapping, WithTitle
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

        foreach ($this->order->productDetails as $productDetail) {
            $rows[] = [
                'Pedido' => $this->order->formatted_id,
                'Cliente' => $this->order->customer->name, // O ->code si prefieres
                'Producto' => $productDetail['product']['name'],
                'Cajas' => $productDetail['boxes'],
                'Peso Neto (kg)' => number_format($productDetail['netWeight'], 2, ',', '.'),
                'Precio Unitario' => number_format($productDetail['unitPrice'], 2, ',', '.'),
                'Subtotal (Base)' => number_format($productDetail['subtotal'], 2, ',', '.'),
                'IVA (%)' => $productDetail['tax']['rate'] ?? 0,
                'Total (con IVA)' => number_format($productDetail['total'], 2, ',', '.'),
                'Fecha Carga' => $this->order->load_date ? $this->order->load_date->format('d/m/Y') : '',
                'Dirección Entrega' => $this->order->shipping_address,
                'Notas' => $this->order->transportation_notes ?? '',

                'CABNUMDOC' => $this->order->formatted_id,
                'CABFECHA' => $this->order->load_date ? $this->order->load_date->format('d/m/Y') : '',
                'CABCODPRO' => $this->order->customer->id,
                'CABREFERENCIA' => $this->order->id,
                'LINCODART' => $productDetail['product']['id'],
                'LINDESCLIN' => $productDetail['product']['name'],
                'LINUNIDADES' => $productDetail['netWeight'],
                'LINPRCMONEDA' => $productDetail['unitPrice'],
                'LINTIPIVA' => $productDetail['tax']['rate'] ?? 0,

            ];
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'Pedido',
            'Cliente',
            'Producto',
            'Cajas',
            'Peso Neto (kg)',
            'Precio Unitario',
            'Subtotal (Base)',
            'IVA (%)',
            'Total (con IVA)',
            'Fecha Carga',
            'Dirección Entrega',
            'Notas',

            'CABNUMDOC',
            'CABFECHA',
            'CABCODPRO',
            'CABREFERENCIA',
            'LINCODART',
            'LINDESCLIN',
            'LINUNIDADES',
            'LINPRCMONEDA',
            'LINTIPIVA'
        ];
    }

    public function map($row): array
    {
        return [
            $row['Pedido'],
            $row['Cliente'],
            $row['Producto'],
            $row['Cajas'],
            $row['Peso Neto (kg)'],
            $row['Precio Unitario'],
            $row['Subtotal (Base)'],
            $row['IVA (%)'],
            $row['Total (con IVA)'],
            $row['Fecha Carga'],
            $row['Dirección Entrega'],
            $row['Notas'],

            $row['CABNUMDOC'],
            $row['CABFECHA'],
            $row['CABCODPRO'],
            $row['CABREFERENCIA'],
            $row['LINCODART'],
            $row['LINDESCLIN'],
            $row['LINUNIDADES'],
            $row['LINPRCMONEDA'],
            $row['LINTIPIVA']

        ];
    }

    /**
     * Nombre personalizado de la hoja
     */
    public function title(): string
    {
        // Puedes poner algo dinámico o fijo
        return 'ALBARANESVENTA';
    }
}

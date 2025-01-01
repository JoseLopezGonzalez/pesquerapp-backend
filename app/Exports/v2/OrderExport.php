<?php

namespace App\Exports\v2;

use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class OrderExport implements FromQuery, WithHeadings, WithMapping
{


    use Exportable;

    protected $filters;

    public function __construct(Request $request)
    {
        $this->filters = $request;
    }

    public function query()
    {
        $query = Order::query();
        if ($this->filters->has('customers')) {
            $query->whereIn('customer_id', $this->filters->customers);
        }

        /* $this->filters->has('id') like id*/
        if ($this->filters->has('id')) {
            $text = $this->filters->id;
            $query->where('id', 'like', "%{$text}%");
        }

        /* ids */
        if ($this->filters->has('ids')) {
            $query->whereIn('id', $this->filters->ids);
        }

        /* buyerReference */
        if ($this->filters->has('buyerReference')) {
            $text = $this->filters->buyerReference;
            $query->where('buyer_reference', 'like', "%{$text}%");
        }

        /* status */
        if ($this->filters->has('status')) {
            $query->where('status', $this->filters->status);
        }

        /* loadDate */
        if ($this->filters->has('loadDate')) {
            $loadDate = $this->filters->input('loadDate');
            /* Check if $loadDate['start'] exists */
            if (isset($loadDate['start'])) {
                $startDate = $loadDate['start'];
                $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
                $query->where('load_date', '>=', $startDate);
            }
            /* Check if $loadDate['end'] exists */
            if (isset($loadDate['end'])) {
                $endDate = $loadDate['end'];
                $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
                $query->where('load_date', '<=', $endDate);
            }
        }

        /* entryDate */
        if ($this->filters->has('entryDate')) {
            $entryDate = $this->filters->input('entryDate');
            if (isset($entryDate['start'])) {
                $startDate = $entryDate['start'];
                $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
                $query->where('entry_date', '>=', $startDate);
            }
            if (isset($entryDate['end'])) {
                $endDate = $entryDate['end'];
                $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
                $query->where('entry_date', '<=', $endDate);
            }
        }

        /* transports */
        if ($this->filters->has('transports')) {
            $query->whereIn('transport_id', $this->filters->transports);
            /* $query->where('customer_id', $this->filters->customer); */
        }

        /* salespeople */
        if ($this->filters->has('salespeople')) {
            $query->whereIn('salesperson_id', $this->filters->salespeople);
            /* $query->where('customer_id', $this->filters->customer); */
        }

        /* palletState */
        if ($this->filters->has('palletsState')) {
            /* if order has any pallets */
            if ($this->filters->palletsState == 'stored') {
                $filters = $this->filters;
                $query->whereHas('pallets', function ($q) use ($filters) {
                    $q->where('state_id', 2);
                });
            } else if ($this->filters->palletsState == 'shipping') {
                /* Solo tiene palets en el estado 3 */
                $filters = $this->filters;
                $query->whereHas('pallets', function ($q) use ($filters) {
                    $q->where('state_id', 3);
                });
            }
        }

        /* incoterm */
        if ($this->filters->has('incoterm')) {
            $query->where('incoterm_id', $this->filters->incoterm);
        }

        $query->orderBy('load_date', 'desc');

        return $query;
    }

    public function map($order): array
    {
        return [
            'Id' => $order->id,
            'Cliente' => $order->customer->name,
            'Referencia' => $order->buyer_reference,
            'Estado' => $order->status,
            'Fecha Carga' => $order->load_date,
            'Comercial' => $order->salesperson->name,
            'Transporte' => $order->transport->name,
            'Palet' => $order->numberOfPallets,
            'Cajas' => $order->totalBoxes,
            'Incoterm' => $order->incoterm->code,
            'Peso Total' => $order->totalNetWeight,
        ];
    }

    public function headings(): array
    {
        return [
            'Id',
            'Cliente',
            'Referencia',
            'Estado',
            'Fecha Carga',
            'Comercial',
            'Transporte',
            'Palet',
            'Cajas',
            'Incoterm',
            'Peso Total',
        ];
    }
}

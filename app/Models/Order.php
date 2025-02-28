<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'payment_term_id', 'billing_address', 'shipping_address', 'transportation_notes', 'production_notes', 'accounting_notes', 'salesperson_id', 'emails', 'transport_id', 'entry_date', 'load_date', 'status', 'buyer_reference', 'incoterm_id'];

    /* Id formateado #00_ _ _ , rellenar con 0 a la izquierda si no tiene 5 digitos y añadir un # al principio */
    public function getFormattedIdAttribute()
    {
        return '#' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    // Relación con Incoterm
    public function incoterm()
    {
        return $this->belongsTo(Incoterm::class, 'incoterm_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesperson()
    {
        return $this->belongsTo(Salesperson::class);
    }

    public function transport()
    {
        return $this->belongsTo(Transport::class);
    }

    public function pallets()
    {
        return $this->hasMany(Pallet::class);
    }

    //Resumen productos pedido


    public function getSummaryAttribute() {}

    public function payment_term()
    {
        return $this->belongsTo(PaymentTerm::class);
    }

    /* Order is active when status is 'finished' and loadDate is < now */
    public function isActive()
    {
        return $this->status == 'pending' || $this->load_date >= now();
    }

    // Resumen de productos agrupados por especie && zona de captura, necesito 

    public function getProductsBySpeciesAndCaptureZoneAttribute()
    {
        $summary = [];
        $this->pallets->map(function ($pallet) use (&$summary) {
            $pallet->boxes->map(function ($box) use (&$summary) {
                $product = $box->box->product;
                $species = $product->species;
                $captureZone = $product->captureZone;
                $key = $species->id . '-' . $captureZone->id;

                if (!isset($summary[$key])) {
                    $summary[$key] = [
                        'species' => $species,
                        'captureZone' => $captureZone,
                        'products' => []
                    ];
                }

                $productKey = $product->id;
                if (!isset($summary[$key]['products'][$productKey])) {
                    $summary[$key]['products'][$productKey] = [
                        'product' => $product,
                        'boxes' => 0,
                        'netWeight' => 0
                    ];
                }

                $summary[$key]['products'][$productKey]['boxes']++;
                $summary[$key]['products'][$productKey]['netWeight'] += $box->netWeight;
            });
        });

        return $summary;
    }


    public function getTotalsAttribute()
    {
        $totals = [
            'boxes' => 0,
            'netWeight' => 0
        ];

        $this->pallets->map(function ($pallet) use (&$totals) {
            $pallet->boxes->map(function ($box) use (&$totals) {
                $totals['boxes']++;
                $totals['netWeight'] += $box->netWeight;
            });
        });

        return $totals;
    }



    public function getNumberOfPalletsAttribute()
    {
        return $this->pallets->count();
    }

    public function getLotsAttribute()
    {

        $lots = [];
        // Asumiendo que $this->pallets es una colección
        $this->pallets->each(function ($pallet) use (&$lots) {
            // Asegúrate de que $pallet->lots sea un array antes de intentar iterar sobre él
            foreach ($pallet->lots as $lot) {
                if (!in_array($lot, $lots)) {
                    $lots[] = $lot;
                }
            }
        });

        return $lots; // Devuelve la lista acumulada de lotes únicos

    }

    /* some pallets on storage status */
    public function hasPalletsOnStorage()
    {
        return $this->pallets->some(function ($pallet) {
            return $pallet->palletState->id == 2;
        });
    }

    /**
     * Get the array of regular emails.
     *
     * @return array
     */
    public function getEmailsArrayAttribute()
    {
        return $this->extractEmails('regular');
    }

    /**
     * Get the array of CC emails.
     *
     * @return array
     */
    public function getCcEmailsArrayAttribute()
    {
        return $this->extractEmails('cc');
    }

    /**
     * Helper method to extract emails based on type.
     *
     * @param string $type 'regular' or 'cc'
     * @return array
     */
    protected function extractEmails($type)
    {
        $emails = explode(';', $this->emails);
        $result = [];

        foreach ($emails as $email) {
            $email = trim($email);
            if (empty($email)) {
                continue;
            }

            if ($type == 'cc' && (str_starts_with($email, 'CC:') || str_starts_with($email, 'cc:'))) {
                $result[] = substr($email, 3);  // Remove 'CC:' prefix and add to results 
            } elseif ($type == 'regular' && !str_starts_with($email, 'CC:') && !str_starts_with($email, 'cc:')) {
                $result[] = $email;  // Add regular email to results
            }
        }

        return $result;
    }


    /* Nuevo V2 */

    public function getTotalNetWeightAttribute()
    {
        return $this->pallets->sum(function ($pallet) {
            return $pallet->boxes->sum('netWeight');
        });
    }

    public function getTotalBoxesAttribute()
    {
        return $this->pallets->sum(function ($pallet) {
            return $pallet->boxes->count();
        });
    }




    /*return [
        [
            'species' => [
                'name'            => 'Pulpo Común',
                'scientificName'  => 'Octopus vulgaris',
                'code'            => 'OCC',
            ],
            'captureZone'      => 'Fao 27 IX.a Atlantico Nordeste',
            'fishingGear'      => 'Nasas y trampas',
            'productionMethod' => 'Capturado',
            'products'         => [
                [
                    'product' => [
                        'name'      => 'Langostinos 20/30',
                        'boxGtin'   => '1234567890123',
                        'boxes'     => 56,
                        'netWeight' => 27.50,
                        ],
                    'lots'      => [
                        [
                            'lot'       => '020325OCC01001',
                            'boxes'     => 2,
                            'netWeight' => 10.50,
                        ],
                        [
                            'lot'       => '020325OCC01001',
                            'boxes'     => 2,
                            'netWeight' => 10.50,
                        ],
                        [
                            'lot'       => '020325OCC01001',
                            'boxes'     => 2,
                            'netWeight' => 10.50,
                        ],
                    ],
                ],
            ],
        ],
    ];  */


    public function getProductsWithLotsDetailsBySpeciesAndCaptureZoneAttribute()
    {
        $summary = [];

        $this->pallets->map(function ($pallet) use (&$summary) {
            $pallet->boxes->map(function ($box) use (&$summary) {
                $product = $box->box->product;
                $species = $product->species;
                $captureZone = $product->captureZone;
                $fishingGear = $species->fishingGear;
                $lot = $box->box->lot; // Suponiendo que cada caja tiene un lote asociado

                $key = $species->id . '-' . $captureZone->id;

                if (!isset($summary[$key])) {
                    $summary[$key] = [
                        'species' => [
                            'name'           => $species->name,
                            'scientificName' => $species->scientific_name,
                            'code'           => $species->code,
                        ],
                        'captureZone'      => $captureZone->name,
                        'fishingGear'      => $fishingGear->name,
                        'products'         => []
                    ];
                }

                $productKey = $product->id;
                if (!isset($summary[$key]['products'][$productKey])) {
                    $summary[$key]['products'][$productKey] = [
                        'product' => [
                            'article'      => [
                                'id'  => $product->article->id,
                                'name' => $product->article->name,
                            ],
                            'boxGtin'   => $product->box_gtin,
                            'boxes'     => 0,
                            'netWeight' => 0,
                        ],
                        'lots' => []
                    ];
                }

                // Agregar detalles del lote
                $summary[$key]['products'][$productKey]['lots'][] = [
                    'lot'       => $lot, // Suponiendo que `lot_number` es el identificador del lote
                    'boxes'     => 1, // Contamos cada caja como una unidad en el lote
                    'netWeight' => $box->box->netWeight,
                ];

                // Sumar totales al producto
                $summary[$key]['products'][$productKey]['product']['boxes']++;
                $summary[$key]['products'][$productKey]['product']['netWeight'] += $box->$box->netWeight;
            });
        });

        return array_values($summary);
    }
}

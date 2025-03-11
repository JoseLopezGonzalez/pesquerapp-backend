<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'payment_term_id', 'billing_address', 'shipping_address', 'transportation_notes', 'production_notes', 'accounting_notes', 'salesperson_id', 'emails', 'transport_id', 'entry_date', 'load_date', 'status', 'buyer_reference', 'incoterm_id'];

    public function plannedProductDetails()
    {
        return $this->hasMany(OrderPlannedProductDetail::class);
    }

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


    public function getSummaryAttribute()
    {
    }

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


    /* public function getProductsWithLotsDetailsBySpeciesAndCaptureZoneAttribute()
    {
        $summary = [];

        $this->pallets->map(function ($pallet) use (&$summary) {
            $pallet->boxes->map(function ($box) use (&$summary) {
                $product = $box->box->product;
                $species = $product->species;
                $captureZone = $product->captureZone;
                $fishingGear = $species->fishingGear;
                $lot = $box->box->lot; // Lote de la caja
                $netWeight = $box->box->net_weight; // Peso neto de la caja

                $key = $species->id . '-' . $captureZone->id;

                if (!isset($summary[$key])) {
                    $summary[$key] = [
                        'species' => [
                            'name' => $species->name,
                            'scientificName' => $species->scientific_name,
                            'fao' => $species->fao,
                        ],
                        'captureZone' => $captureZone->name,
                        'fishingGear' => $fishingGear->name,
                        'products' => []
                    ];
                }

                $productKey = $product->id;
                if (!isset($summary[$key]['products'][$productKey])) {
                    $summary[$key]['products'][$productKey] = [
                        'product' => [
                            'article' => [
                                'id' => $product->article->id,
                                'name' => $product->article->name,
                            ],
                            'boxGtin' => $product->box_gtin,
                            'boxes' => 0,
                            'netWeight' => 0,
                        ],
                        'lots' => []
                    ];
                }

                // Agrupar lotes únicos y sumar pesos y cajas
                $lotIndex = array_search($lot, array_column($summary[$key]['products'][$productKey]['lots'], 'lot'));

                if ($lotIndex === false) {
                    // Si el lote no existe, lo añadimos
                    $summary[$key]['products'][$productKey]['lots'][] = [
                        'lot' => $lot,
                        'boxes' => 1,
                        'netWeight' => $netWeight,
                    ];
                } else {
                    // Si ya existe, sumamos los valores
                    $summary[$key]['products'][$productKey]['lots'][$lotIndex]['boxes']++;
                    $summary[$key]['products'][$productKey]['lots'][$lotIndex]['netWeight'] += $netWeight;
                }

                // Sumar totales al producto
                $summary[$key]['products'][$productKey]['product']['boxes']++;
                $summary[$key]['products'][$productKey]['product']['netWeight'] += $netWeight;
            });
        });

        return array_values($summary);
    } */

    public function getProductsWithLotsDetailsAttribute()
    {
        $summary = [];

        $this->pallets->map(function ($pallet) use (&$summary) {
            $pallet->boxes->map(function ($box) use (&$summary) {
                $product = $box->box->product;
                $lot = $box->box->lot; // Lote de la caja
                $netWeight = $box->box->net_weight; // Peso neto de la caja

                $productKey = $product->id;

                if (!isset($summary[$productKey])) {
                    $summary[$productKey] = [
                        'product' => [
                            'article' => [
                                'id' => $product->article->id,
                                'name' => $product->article->name,
                            ],
                            'boxGtin' => $product->box_gtin,
                            'boxes' => 0,
                            'netWeight' => 0,
                            'species' => [
                                'name' => $product->species->name,
                                'scientificName' => $product->species->scientific_name,
                                'fao' => $product->species->fao,
                            ],
                            'captureZone' => $product->captureZone->name,
                            'fishingGear' => $product->species->fishingGear->name,
                        ],
                        'lots' => []
                    ];
                }

                // Agrupar lotes únicos y sumar pesos y cajas
                $lotIndex = array_search($lot, array_column($summary[$productKey]['lots'], 'lot'));

                if ($lotIndex === false) {
                    // Si el lote no existe, lo añadimos
                    $summary[$productKey]['lots'][] = [
                        'lot' => $lot,
                        'boxes' => 1,
                        'netWeight' => $netWeight,
                    ];
                } else {
                    // Si ya existe, sumamos los valores
                    $summary[$productKey]['lots'][$lotIndex]['boxes']++;
                    $summary[$productKey]['lots'][$lotIndex]['netWeight'] += $netWeight;
                }

                // Sumar totales al producto
                $summary[$productKey]['product']['boxes']++;
                $summary[$productKey]['product']['netWeight'] += $netWeight;
            });
        });

        return array_values($summary);
    }

    /* obtener un listado de productos con cantidades y numero de cajas de todos los palets vinculados */
    public function getProductionProductDetailsAttribute()
    {
        $details = [];
        $this->pallets->map(function ($pallet) use (&$details) {
            $pallet->boxes->map(function ($box) use (&$details) {
                $product = $box->box->product;
                $productKey = $product->id;
                if (!isset($details[$productKey])) {
                    $details[$productKey] = [
                        'product' => $product,
                        'boxes' => 0,
                        'netWeight' => 0,
                    ];
                }

                $details[$productKey]['boxes']++;
                $details[$productKey]['netWeight'] += $box->netWeight;
            });
        });

        return array_values($details);
    }


    /* Confrontar en un mismo array productionProductDetails añadiendo el precio y tax sacado de plannedProductDetail y calculando
    el subtotal (base sin tax) y total (base + tax) */

    public function getProductDetailsAttribute()
    {
        $productionProductDetails = $this->productionProductDetails;
        $plannedProductDetails = $this->plannedProductDetails;

        $details = [];
        foreach ($productionProductDetails as $productionProductDetail) {

            $product = $productionProductDetail['product'];
            $productKey = $product->id;
            $details[$productKey]['product'] = $product;
            $details[$productKey]['boxes'] = $productionProductDetail['boxes'];
            $details[$productKey]['netWeight'] = $productionProductDetail['netWeight'];

            /* buscar este producto en plannedProductDetails*/
            $plannedProductDetail = $plannedProductDetails->firstWhere('product_id', $product->id);
            if ($plannedProductDetail) {
                $details[$productKey]['unitPrice'] = $plannedProductDetail->unit_price;
                $details[$productKey]['tax'] = $plannedProductDetail->tax;
                $details[$productKey]['subtotal'] = $details[$productKey]['unitPrice'] * $details[$productKey]['netWeight'];
                $details[$productKey]['total'] = $details[$productKey]['subtotal'] + ($details[$productKey]['subtotal'] * $details[$productKey]['tax']->rate / 100);
            } else {
                $details[$productKey]['unitPrice'] = 0;
                $details[$productKey]['tax'] = 0;
                $details[$productKey]['subtotal'] = 0;
                $details[$productKey]['total'] = 0;
            }


        }


        return array_values($details);
    }


}

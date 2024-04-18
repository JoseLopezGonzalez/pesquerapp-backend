<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'payment_term_id', 'billing_address', 'shipping_address', 'transportation_notes', 'production_notes', 'accounting_notes', 'salesperson_id', 'emails', 'transport_id', 'entry_date', 'load_date', 'status'];

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

    public function getProductsBySpeciesAndCaptureZone()
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
}

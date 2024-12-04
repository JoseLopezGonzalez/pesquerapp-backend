<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;

    protected $fillable = [
        'lot',
        'date',
        'species_id',
        'capture_zone_id',
        'notes',
        'diagram_data',
    ];

    protected $casts = [
        'diagram_data' => 'array', // Casteo para manipular JSON como array
    ];

    /* diagram_data->totalProfit  si esque existe alguna clave */
    public function getTotalProfitAttribute()
    {
        // Decodificar `diagram_data` en caso de que sea una cadena JSON
        $diagramData = is_string($this->diagram_data) ? json_decode($this->diagram_data, true) : $this->diagram_data;

        // Verificar si `diagramData` es ahora un array y contiene la estructura que necesitamos
        return is_array($diagramData) &&
            isset($diagramData['totals']) &&
            is_array($diagramData['totals']) &&
            array_key_exists('totalProfit', $diagramData['totals'])
            ? $diagramData['totals']['totalProfit']
            : null;
    }


    public function getTotalProfitPerInputKgAttribute()
    {
        // Decodificar `diagram_data` en caso de que sea una cadena JSON
        $diagramData = is_string($this->diagram_data) ? json_decode($this->diagram_data, true) : $this->diagram_data;

        // Verificar si `diagramData` es ahora un array y contiene la estructura que necesitamos
        return is_array($diagramData) &&
            isset($diagramData['totals']) &&
            is_array($diagramData['totals']) &&
            array_key_exists('totalProfitPerInputKg', $diagramData['totals'])
            ? $diagramData['totals']['totalProfitPerInputKg']
            : null;
    }



    public function getProcessNodes()
    {
        // Decodificar diagram_data
        $diagramData = is_string($this->diagram_data) ? json_decode($this->diagram_data, true) : $this->diagram_data;
        $processNodes = $diagramData['processNodes'] ?? [];
    
        // Extraer los datos clave de cada nodo
        return collect($processNodes)->map(function ($node) {
            return [
                'node_id' => $node['id'],
                'process_name' => $node['process']['name'] ?? 'Sin nombre',
                'input_quantity' => $node['inputQuantity'] ?? 0,
                'decrease' => $node['decrease'] ?? 0, // Merma
            ];
        });
    }

    public function getFinalNodes()
    {
        $diagramData = is_string($this->diagram_data) ? json_decode($this->diagram_data, true) : $this->diagram_data;
        $finalNodes = $diagramData['finalNodes'] ?? [];
    
        return collect($finalNodes)->map(function ($node) {
            $totals = $node['production']['totals'] ?? [];
            $profits = $node['profits']['totals'] ?? [];
    
            // Detalle de productos
            $productDetails = collect($node['production']['details'] ?? [])->map(function ($detail) use ($node) {
                $salesDetails = collect($node['sales']['details'] ?? [])
                    ->firstWhere('product.id', $detail['product']['id']);
    
                $pricePerKg = $salesDetails['price'] ?? 0;
                /* $costPerKg = $detail['costPerKg'] ?? 0; */

                /* Nuevo */
                $productionSummary = collect($node['production']['summary'] ?? [])
                    ->firstWhere('product.id', $detail['product']['id']);

                $costPerKg = $productionSummary['averageCostPerKg'] ?? 0;

                $profitsSummary = collect($node['profits']['summary'] ?? [])
                    ->firstWhere('product.id', $detail['product']['id']);

                $profitPerInputKg = $profitsSummary['profitPerInputKg'] ?? 0;
                $profitPerOutputKg = $profitsSummary['profitPerOutputKg'] ?? 0;
    
                return [
                    'product_name' => $detail['product']['name'] ?? 'Producto desconocido',
                    'quantity' => is_numeric($detail['quantity']) ? $detail['quantity'] : 0,
                    'cost_per_kg' => is_numeric($costPerKg) ? $costPerKg : 0,
                    'profit_per_output_kg' => is_numeric($profitPerOutputKg) ? $profitPerOutputKg : 0,
                    'profit_per_input_kg' => is_numeric($profitPerInputKg) ? $profitPerInputKg : 0,
                ];
            });
    
           /* 
           ANTIGUO
           return [
                'node_id' => $node['id'] ?? null,
                'process_name' => $node['process']['name'] ?? 'Sin nombre',
                'total_quantity' => is_numeric($totals['quantity'] ?? null) ? $totals['quantity'] : 0,
                'profit_per_kg' => is_numeric($profits['averageProfitPerKg'] ?? null) ? $profits['averageProfitPerKg'] : 0,
                'cost_per_kg' => is_numeric($totals['averageCostPerKg'] ?? null) ? $totals['averageCostPerKg'] : 0,
                'products' => $productDetails->toArray(),
            ]; */

            return [
                'node_id' => $node['id'] ?? null,
                'process_name' => $node['process']['name'] ?? 'Sin nombre',
                'total_quantity' => is_numeric($totals['quantity'] ?? null) ? $totals['quantity'] : 0,
                // Renombrar profit_per_kg a profit_per_output_kg
                'profit_per_output_kg' => is_numeric($profits['averageProfitPerKg'] ?? null) ? $profits['averageProfitPerKg'] : 0,
                // Implementar profit_per_input_kg utilizando $node['averageProfitPerInputKg']
                'profit_per_input_kg' => is_numeric($node['averageProfitPerInputKg'] ?? null) ? $node['averageProfitPerInputKg'] : 0,
                'cost_per_kg' => is_numeric($totals['averageCostPerKg'] ?? null) ? $totals['averageCostPerKg'] : 0,
                'products' => $productDetails->toArray(),
            ];
        });
    }
    
    

    


    






    // Relación con el modelo Species
    public function species()
    {
        return $this->belongsTo(Species::class, 'species_id');
    }

    // Relación con el modelo CaptureZone
    public function captureZone()
    {
        return $this->belongsTo(CaptureZone::class, 'capture_zone_id');
    }
}

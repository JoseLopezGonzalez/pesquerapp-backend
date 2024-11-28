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
            // Usar production.totals.quantity como referencia
            $totals = $node['production']['totals'] ?? [];
            $totalQuantity = $totals['quantity'] ?? 0;
    
            // Obtener beneficio medio por kg
            $profits = $node['profits']['totals'] ?? [];
            $profitPerKg = $profits['averageProfitPerKg'] ?? 0;
    
            return [
                'node_id' => $node['id'] ?? null,
                'process_name' => $node['process']['name'] ?? 'Sin nombre',
                'total_quantity' => is_numeric($totalQuantity) ? $totalQuantity : 0,
                'profit_per_kg' => is_numeric($profitPerKg) ? $profitPerKg : 0,
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

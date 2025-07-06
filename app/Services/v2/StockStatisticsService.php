<?php

namespace App\Services\v2;

use App\Models\Pallet;
use App\Models\Species;
use App\Models\StoredPallet;

class StockStatisticsService
{
    public static function getTotalStockStats(): array
    {
        $totalWeight = Pallet::query()
            ->stored()
            ->joinBoxes()
            ->sum('boxes.net_weight');

        $totalPallets = Pallet::stored()->count();

        $totalBoxes = Pallet::stored()
            ->join('pallet_boxes', 'pallet_boxes.pallet_id', '=', 'pallets.id')
            ->count('pallet_boxes.id');

        $totalSpecies = Pallet::stored()
            ->joinProducts()
            ->distinct('products.species_id')
            ->count('products.species_id');


        $totalStores = StoredPallet::stored()
            ->distinct('stored_pallets.store_id')
            ->count('stored_pallets.store_id');

        return [
            'totalNetWeight' => round($totalWeight, 2),
            'totalPallets' => $totalPallets,
            'totalBoxes' => $totalBoxes,
            'totalSpecies' => $totalSpecies,
            'totalStores' => $totalStores,
        ];
    }

    public static function getSpeciesTotalsRaw(): \Illuminate\Support\Collection
    {
        return Pallet::stored()
            ->joinProducts()
            ->selectRaw('products.species_id, SUM(boxes.net_weight) as total_kg')
            ->groupBy('products.species_id')
            ->get();
    }

    public static function getTotalStockBySpeciesStats(): array
    {
        $rows = self::getSpeciesTotalsRaw();

        $speciesList = Species::whereIn('id', $rows->pluck('species_id'))->get()->keyBy('id');

        $data = $rows->map(function ($row) use ($speciesList) {
            $species = $speciesList[$row->species_id] ?? null;
            return [
                'id' => $row->species_id,
                'name' => $species?->name ?? 'Desconocida',
                'totalNetWeight' => round($row->totalNetWeight, 2),
            ];
        })->filter(fn($item) => $item['id'] !== null)->values();

        $totalNetWeight = $data->sum('totalNetWeight');

        return $data->map(function ($item) use ($totalNetWeight) {
            $item['percentage'] = $totalNetWeight > 0
                ? round(($item['totalNetWeight'] / $totalNetWeight) * 100, 2)
                : 0;
            return $item;
        })->sortByDesc('totalNetWeight')->values()->toArray();
    }
}

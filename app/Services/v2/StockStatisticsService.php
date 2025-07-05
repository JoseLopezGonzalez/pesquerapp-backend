<?php

namespace App\Services\v2;

use App\Models\Pallet;
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
            ->joinArticles()
            ->distinct('articles.species_id')
            ->count('articles.species_id');

        $totalStores = StoredPallet::stored()
            ->distinct('stored_pallets.store_id')
            ->count('stored_pallets.store_id');

        return [
            'totalWeight' => round($totalWeight, 2),
            'totalPallets' => $totalPallets,
            'totalBoxes' => $totalBoxes,
            'totalSpecies' => $totalSpecies,
            'totalStores' => $totalStores,
        ];
    }
}

<?php

namespace App\Services\v2;

use App\Models\Order;

class OrderStatisticsService
{

    public static function prepareDateRangeAndPrevious(string $dateFrom, string $dateTo): array
    {
        $from = $dateFrom . ' 00:00:00';
        $to = $dateTo . ' 23:59:59';

        $fromPrev = date('Y-m-d H:i:s', strtotime($from . ' -1 year'));
        $toPrev = date('Y-m-d H:i:s', strtotime($to . ' -1 year'));

        return [
            'from' => $from,
            'to' => $to,
            'fromPrev' => $fromPrev,
            'toPrev' => $toPrev,
        ];
    }

    public static function calculateTotalNetWeight(string $from, string $to, ?int $speciesId = null): float
    {
        $query = Order::query()
            ->joinBoxesAndArticles()
            ->whereBoxArticleSpecies($speciesId)
            ->betweenLoadDates($from, $to);

        return Order::executeNetWeightSum($query);
    }


    public static function compareTotals(float $current, float $previous): ?float
    {
        if ($previous == 0) {
            return null;
        }

        return (($current - $previous) / $previous) * 100;
    }

    public static function getNetWeightStatsComparedToLastYear(string $dateFrom, string $dateTo, ?int $speciesId = null): array
    {
        $range = self::prepareDateRangeAndPrevious($dateFrom, $dateTo);

        $totalCurrent = self::calculateTotalNetWeight($range['from'], $range['to'], $speciesId);
        $totalPrevious = self::calculateTotalNetWeight($range['fromPrev'], $range['toPrev'], $speciesId);

        return [
            'value' => round($totalCurrent, 2),
            'comparisonValue' => round($totalPrevious, 2),
            'percentageChange' => self::compareTotals($totalCurrent, $totalPrevious) !== null
                ? round(self::compareTotals($totalCurrent, $totalPrevious), 2)
                : null,
            'range' => [
                'from' => $range['from'],
                'to' => $range['to'],
                'fromPrev' => $range['fromPrev'],
                'toPrev' => $range['toPrev'],
            ]
        ];
    }


    /* public static function calculateTotalAmount(string $from, string $to, ?int $speciesId = null): float
    {
        return Order::query()
            ->withPlannedProductDetails()
            ->wherePlannedProductSpecies($speciesId)
            ->betweenLoadDates($from, $to)
            ->get()
            ->sum(fn($order) => $order->totalAmount);
    }

    public static function calculateSubtotalAmount(string $from, string $to, ?int $speciesId = null): float
    {
        return Order::query()
            ->withPlannedProductDetails()
            ->wherePlannedProductSpecies($speciesId)
            ->betweenLoadDates($from, $to)
            ->get()
            ->sum(fn($order) => $order->subtotalAmount);
    } */

    public static function calculateAmountDetails(string $from, string $to, ?int $speciesId = null): array
    {
        $orders = Order::query()
            ->withPlannedProductDetails()
            ->wherePlannedProductSpecies($speciesId)
            ->betweenLoadDates($from, $to)
            ->get();

        $total = 0;
        $subtotal = 0;

        foreach ($orders as $order) {
            $total += $order->totalAmount;
            $subtotal += $order->subtotalAmount;
        }

        $tax = $total - $subtotal;

        return [
            'total' => $total,
            'subtotal' => $subtotal,
            'tax' => $tax,
        ];
    }



    public static function getAmountStatsComparedToLastYear(string $dateFrom, string $dateTo, ?int $speciesId = null): array
    {
        $range = self::prepareDateRangeAndPrevious($dateFrom, $dateTo);

        $current = self::calculateAmountDetails($range['from'], $range['to'], $speciesId);
        $previous = self::calculateAmountDetails($range['fromPrev'], $range['toPrev'], $speciesId);

        return [
            'value' => round($current['total'], 2),
            'subtotal' => round($current['subtotal'], 2),
            'tax' => round($current['tax'], 2),

            'comparisonValue' => round($previous['total'], 2),
            'comparisonSubtotal' => round($previous['subtotal'], 2),
            'comparisonTax' => round($previous['tax'], 2),

            'percentageChange' => self::compareTotals($current['total'], $previous['total']) !== null
                ? round(self::compareTotals($current['total'], $previous['total']), 2)
                : null,

            'range' => $range,
        ];
    }


    public static function getOrderRankingStats(string $groupBy, string $valueType, string $dateFrom, string $dateTo, ?int $speciesId = null): \Illuminate\Support\Collection
    {
        $orders = Order::query()
            ->withCustomerCountry()
            ->withPlannedProductDetailsAndSpecies()
            ->wherePlannedProductSpecies($speciesId)
            ->betweenLoadDates($dateFrom, $dateTo)
            ->get();

        $summary = [];

        foreach ($orders as $order) {
            $products = $order->plannedProductDetails;

            if ($speciesId) {
                $products = $products->filter(fn($p) => $p->product?->species_id === $speciesId);
            }

            foreach ($products as $p) {
                $groupName = match ($groupBy) {
                    'client' => $order->customer->name,
                    'country' => $order->customer->country->name ?? 'Sin país',
                    'product' => $p->product->name ?? 'Sin producto',
                };

                if (!isset($summary[$groupName])) {
                    $summary[$groupName] = [
                        'name' => $groupName,
                        'totalQuantity' => 0,
                        'totalAmount' => 0,
                    ];
                }

                $summary[$groupName]['totalQuantity'] += $p->net_weight ?? 0;
                $summary[$groupName]['totalAmount'] += $p->total ?? 0;
            }
        }

        return collect(array_values($summary))
            ->sortByDesc($valueType)
            ->values()
            ->map(fn($item) => [
                'name' => $item['name'],
                'value' => round($item[$valueType], 2),
            ]);
    }










}



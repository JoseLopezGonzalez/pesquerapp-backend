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


    public static function calculateTotalAmount(string $from, string $to, ?int $speciesId = null): float
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
    }


    public static function getAmountStatsComparedToLastYear(string $dateFrom, string $dateTo, ?int $speciesId = null): array
    {
        $range = self::prepareDateRangeAndPrevious($dateFrom, $dateTo);

        $totalCurrent = self::calculateTotalAmount($range['from'], $range['to'], $speciesId);
        $subtotalCurrent = self::calculateSubtotalAmount($range['from'], $range['to'], $speciesId);
        $taxCurrent = $totalCurrent - $subtotalCurrent;

        $totalPrevious = self::calculateTotalAmount($range['fromPrev'], $range['toPrev'], $speciesId);
        $subtotalPrevious = self::calculateSubtotalAmount($range['fromPrev'], $range['toPrev'], $speciesId);
        $taxPrevious = $totalPrevious - $subtotalPrevious;

        return [
            'value' => round($totalCurrent, 2),
            'subtotal' => round($subtotalCurrent, 2),
            'tax' => round($taxCurrent, 2),

            'comparisonValue' => round($totalPrevious, 2),
            'comparisonSubtotal' => round($subtotalPrevious, 2),
            'comparisonTax' => round($taxPrevious, 2),

            'percentageChange' => self::compareTotals($totalCurrent, $totalPrevious) !== null
                ? round(self::compareTotals($totalCurrent, $totalPrevious), 2)
                : null,

            'range' => $range,
        ];
    }






}



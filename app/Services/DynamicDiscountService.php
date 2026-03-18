<?php

namespace App\Services;

use App\Models\Product;
use Carbon\Carbon;

class DynamicDiscountService
{
    const BASE_DISCOUNT = 0.10;
    const MAX_TIME_DISCOUNT = 0.60;
    const MAX_TOTAL_DISCOUNT = 0.90;
    const PRICE_FLOOR_RATIO = 0.10;
    const POST_DEADLINE_RATIO = 0.05;

    const CATEGORY_MULTIPLIERS = [
        'dairy' => 1.20,
        'meat' => 1.20,
        'seafood' => 1.20,
        'bakery' => 1.10,
        'bread' => 1.10,
        'produce' => 1.05,
        'general' => 1.00,
        'beverage' => 0.95,
    ];

    public function calculatePrice(Product $product): float
    {
        $originalPrice = (float)$product->original_price;
        $now = Carbon::now();
        $deadline = Carbon::parse($product->pickup_deadline);

        if ($now->greaterThanOrEqualTo($deadline)) {
            $postDeadlinePrice = $originalPrice * self::POST_DEADLINE_RATIO;
            return round(max($postDeadlinePrice, 0.01), 2);
        }

        $createdAt = $product->created_at ?? $deadline->copy()->subHours(8);
        $totalWindow = max($createdAt->diffInSeconds($deadline), 1);
        $elapsed = $createdAt->diffInSeconds($now);
        $timeProgress = min(max($elapsed / $totalWindow, 0.0), 1.0);

        $timeDiscount = ($timeProgress ** 2) * self::MAX_TIME_DISCOUNT;
        $stock = (int)$product->stock;
        $stockDiscount = $this->calculateStockDiscount($stock);

        $category = strtolower($product->category ?? 'general');
        $multiplier = self::CATEGORY_MULTIPLIERS[$category] ?? 1.0;

        $totalDiscount = (self::BASE_DISCOUNT + $timeDiscount + $stockDiscount) * $multiplier;
        $totalDiscount = min($totalDiscount, self::MAX_TOTAL_DISCOUNT);

        $finalPrice = $originalPrice * (1 - $totalDiscount);
        $priceFloor = $originalPrice * self::PRICE_FLOOR_RATIO;
        $finalPrice = max($finalPrice, $priceFloor);

        return round($finalPrice, 2);
    }

    private function calculateStockDiscount(int $stock): float
    {
        if ($stock > 50)
            return 0.20;
        if ($stock > 20)
            return 0.15;
        if ($stock > 10)
            return 0.05;
        return 0.00;
    }

    public function getDiscountBreakdown(Product $product): array
    {
        $originalPrice = (float)$product->original_price;
        $now = Carbon::now();
        $deadline = Carbon::parse($product->pickup_deadline);

        if ($now->greaterThanOrEqualTo($deadline)) {
            return [
                'original_price' => $originalPrice,
                'dynamic_price' => round($originalPrice * self::POST_DEADLINE_RATIO, 2),
                'discount_percentage' => round((1 - self::POST_DEADLINE_RATIO) * 100, 1),
                'breakdown' => [
                    'base' => 0,
                    'time' => 0,
                    'stock' => 0,
                    'category_multiplier' => 1.0,
                    'total' => round((1 - self::POST_DEADLINE_RATIO) * 100, 1),
                ],
                'time_progress' => 1.0,
                'urgency' => 'expired',
            ];
        }

        $createdAt = $product->created_at ?? $deadline->copy()->subHours(8);
        $totalWindow = max($createdAt->diffInSeconds($deadline), 1);
        $elapsed = $createdAt->diffInSeconds($now);
        $timeProgress = min(max($elapsed / $totalWindow, 0.0), 1.0);

        $timeDiscount = ($timeProgress ** 2) * self::MAX_TIME_DISCOUNT;
        $stockDiscount = $this->calculateStockDiscount((int)$product->stock);
        $category = strtolower($product->category ?? 'general');
        $multiplier = self::CATEGORY_MULTIPLIERS[$category] ?? 1.0;

        $totalDiscount = min((self::BASE_DISCOUNT + $timeDiscount + $stockDiscount) * $multiplier, self::MAX_TOTAL_DISCOUNT);
        $finalPrice = max($originalPrice * (1 - $totalDiscount), $originalPrice * self::PRICE_FLOOR_RATIO);

        $minutesLeft = now()->diffInMinutes($deadline, false);
        $urgency = match (true) {
                $minutesLeft <= 0 => 'expired',
                $minutesLeft <= 60 => 'critical',
                $minutesLeft <= 180 => 'high',
                $minutesLeft <= 360 => 'medium',
                default => 'low',
            };

        return [
            'original_price' => $originalPrice,
            'dynamic_price' => round($finalPrice, 2),
            'discount_percentage' => round($totalDiscount * 100, 1),
            'breakdown' => [
                'base' => self::BASE_DISCOUNT * 100,
                'time' => round($timeDiscount * 100, 1),
                'stock' => round($stockDiscount * 100, 1),
                'category_multiplier' => $multiplier,
                'total' => round($totalDiscount * 100, 1),
            ],
            'time_progress' => round($timeProgress, 4),
            'urgency' => $urgency,
        ];
    }
}
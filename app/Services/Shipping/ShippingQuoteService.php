<?php

namespace App\Services\Shipping;

/**
 * Pluggable shipping quotes. Swap {@see calculate()} for EasyPost, ShipEngine, Shippo, etc.
 */
class ShippingQuoteService
{
    public function calculate(float $subtotal, string $country = 'US'): array
    {
        $base = (float) config('shop.shipping_flat_rate', 12.99);
        $freeAbove = (float) config('shop.shipping_free_above', 150);

        if ($subtotal >= $freeAbove) {
            return [
                'amount' => 0.0,
                'carrier' => 'standard',
                'service_code' => 'FREE',
                'estimated_days_min' => 3,
                'estimated_days_max' => 5,
            ];
        }

        return [
            'amount' => $base,
            'carrier' => 'standard',
            'service_code' => 'GROUND',
            'estimated_days_min' => 4,
            'estimated_days_max' => 7,
        ];
    }

    public function estimatedDelivery(?int $minDays = null, ?int $maxDays = null): ?\DateTimeInterface
    {
        $minDays ??= 4;
        $maxDays ??= 7;
        $avg = (int) round(($minDays + $maxDays) / 2);

        return now()->addWeekdays($avg);
    }
}

<?php

namespace App\Services;

class DeliveryFeeCalculator
{
    public function calculate(float $distanceKm): array
    {
        $normalizedDistance = max(0, $distanceKm);
        $baseFee = 20000;
        $extraDistance = max(0, $normalizedDistance - 3);
        $extraKilometers = (int) ceil($extraDistance);
        $extraFee = $extraKilometers * 2000;

        return [
            'distance_km' => round($normalizedDistance, 2),
            'base_distance_km' => 3,
            'base_fee_syp' => $baseFee,
            'extra_distance_km' => round($extraDistance, 2),
            'extra_kilometers_charged' => $extraKilometers,
            'extra_fee_syp' => $extraFee,
            'total_fee_syp' => $baseFee + $extraFee,
        ];
    }
}
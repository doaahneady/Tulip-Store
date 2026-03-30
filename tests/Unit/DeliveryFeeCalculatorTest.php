<?php

namespace Tests\Unit;

use App\Services\DeliveryFeeCalculator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DeliveryFeeCalculatorTest extends TestCase
{
    #[DataProvider('distances')]
    public function test_delivery_fee_breakdown(float $distanceKm, int $expectedTotal, int $expectedExtraKm): void
    {
        $breakdown = app(DeliveryFeeCalculator::class)->calculate($distanceKm);

        $this->assertSame($expectedTotal, $breakdown['total_fee_syp']);
        $this->assertSame($expectedExtraKm, $breakdown['extra_kilometers_charged']);
    }

    public static function distances(): array
    {
        return [
            '0 km' => [0.0, 20000, 0],
            '2.9 km' => [2.9, 20000, 0],
            '3 km' => [3.0, 20000, 0],
            '5.3 km' => [5.3, 26000, 3],
            '10 km' => [10.0, 34000, 7],
        ];
    }
}
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CheckoutDeliveryFeeApiTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('distances')]
    public function test_delivery_fee_quote_api(float $distanceKm, int $expectedTotal): void
    {
        $response = $this->postJson('/api/checkout/delivery-fee', [
            'distance_km' => $distanceKm,
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('delivery_fee_breakdown.total_fee_syp', $expectedTotal);
    }

    public static function distances(): array
    {
        return [
            '0 km' => [0.0, 20000],
            '2.9 km' => [2.9, 20000],
            '3 km' => [3.0, 20000],
            '5.3 km' => [5.3, 26000],
            '10 km' => [10.0, 34000],
        ];
    }
}
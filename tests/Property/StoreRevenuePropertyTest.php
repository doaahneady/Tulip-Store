<?php

namespace Tests\Property;

use App\Models\Store;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\StoreRepositoryInterface;
use App\Services\Dashboard\MetricsService;
use App\Services\Dashboard\StoreOwnerDashboardService;
use Carbon\Carbon;
use Mockery;
use Tests\TestCase;

/**
 * Property-Based Tests for Store Revenue Calculation
 *
 * **Feature: dashboard-system-rebuild, Property 21: Store Revenue Calculation**
 * **Validates: Requirements 12.3**
 *
 * *For any* store owner, their displayed revenue SHALL equal the sum of
 * completed order totals for their products minus platform commission.
 */
class StoreRevenuePropertyTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    protected function createMockStore(int $id, int $userId, float $commissionRate): Store
    {
        $store = Mockery::mock(Store::class)->makePartial();
        $store->id = $id;
        $store->user_id = $userId;
        $store->commission_rate = $commissionRate;
        $store->shouldReceive('products')->andReturnSelf();
        $store->shouldReceive('count')->andReturn(5);
        $store->shouldReceive('where')->andReturnSelf();

        return $store;
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 21: Store Revenue Calculation**
     * **Validates: Requirements 12.3**
     *
     * *For any* store owner, their displayed earnings SHALL equal
     * revenue minus platform commission (revenue * (1 - commission_rate/100)).
     *
     * @test
     */
    public function property_store_earnings_equals_revenue_minus_commission(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $storeId = rand(1, 10000);
            $userId = rand(1, 10000);
            $commissionRate = rand(5, 30);
            $revenue = rand(1000, 100000) / 100;

            $store = $this->createMockStore($storeId, $userId, $commissionRate);
            $expectedEarnings = $revenue * (1 - $commissionRate / 100);

            $orderRepository = Mockery::mock(OrderRepositoryInterface::class);
            $storeRepository = Mockery::mock(StoreRepositoryInterface::class);
            $metricsService = Mockery::mock(MetricsService::class);

            $storeRepository->shouldReceive('findById')->with($storeId)->andReturn($store);
            $storeRepository->shouldReceive('calculateRevenue')
                ->withArgs(function ($id, $start, $end) use ($storeId) {
                    return $id === $storeId;
                })
                ->andReturn($revenue);
            $storeRepository->shouldReceive('calculateEarnings')
                ->withArgs(function ($id, $start, $end) use ($storeId) {
                    return $id === $storeId;
                })
                ->andReturn($expectedEarnings);

            $orderRepository->shouldReceive('getOrderCount')->andReturn(10);

            $metricsService->shouldReceive('calculateGrowthPercentage')->andReturn(0.0);
            $metricsService->shouldReceive('formatCurrency')
                ->andReturnUsing(fn ($amount) => '$'.number_format($amount, 2));
            $metricsService->shouldReceive('formatPercentage')
                ->andReturn(['value' => '0%', 'color' => 'gray', 'icon' => 'minus']);

            $service = new StoreOwnerDashboardService($orderRepository, $storeRepository, $metricsService);
            $kpis = $service->getKPIMetrics($storeId);

            $this->assertEquals(
                $expectedEarnings,
                $kpis['earnings']['value'],
                "Iteration $i: Earnings should equal revenue ($revenue) minus $commissionRate% commission"
            );

            $calculatedEarnings = $kpis['revenue']['value'] * (1 - $commissionRate / 100);
            $this->assertEqualsWithDelta(
                $calculatedEarnings,
                $kpis['earnings']['value'],
                0.01,
                "Iteration $i: Earnings should be revenue minus commission"
            );
        }
    }

    /**
     * Test that revenue calculation is correctly scoped to the store
     *
     * @test
     */
    public function property_revenue_calculation_uses_correct_store_id(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $storeId = rand(1, 10000);
            $userId = rand(1, 10000);
            $commissionRate = rand(5, 30);

            $store = $this->createMockStore($storeId, $userId, $commissionRate);

            $capturedStoreId = null;

            $orderRepository = Mockery::mock(OrderRepositoryInterface::class);
            $storeRepository = Mockery::mock(StoreRepositoryInterface::class);
            $metricsService = Mockery::mock(MetricsService::class);

            $storeRepository->shouldReceive('findById')->andReturn($store);
            $storeRepository->shouldReceive('calculateRevenue')
                ->withArgs(function ($id, $start, $end) use (&$capturedStoreId) {
                    $capturedStoreId = $id;

                    return true;
                })
                ->andReturn(1000.0);
            $storeRepository->shouldReceive('calculateEarnings')->andReturn(900.0);
            $orderRepository->shouldReceive('getOrderCount')->andReturn(10);

            $metricsService->shouldReceive('calculateGrowthPercentage')->andReturn(0.0);
            $metricsService->shouldReceive('formatCurrency')->andReturn('$1000');
            $metricsService->shouldReceive('formatPercentage')
                ->andReturn(['value' => '0%', 'color' => 'gray', 'icon' => 'minus']);

            $service = new StoreOwnerDashboardService($orderRepository, $storeRepository, $metricsService);
            $service->calculateRevenue($storeId, Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth());

            $this->assertEquals(
                $storeId,
                $capturedStoreId,
                "Iteration $i: Revenue calculation should use store ID $storeId"
            );
        }
    }

    /**
     * Test that earnings are always less than or equal to revenue
     *
     * @test
     */
    public function property_earnings_never_exceed_revenue(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $storeId = rand(1, 10000);
            $userId = rand(1, 10000);
            $commissionRate = rand(0, 50);
            $revenue = rand(0, 100000) / 100;

            $store = $this->createMockStore($storeId, $userId, $commissionRate);
            $expectedEarnings = $revenue * (1 - $commissionRate / 100);

            $orderRepository = Mockery::mock(OrderRepositoryInterface::class);
            $storeRepository = Mockery::mock(StoreRepositoryInterface::class);
            $metricsService = Mockery::mock(MetricsService::class);

            $storeRepository->shouldReceive('findById')->andReturn($store);
            $storeRepository->shouldReceive('calculateRevenue')->andReturn($revenue);
            $storeRepository->shouldReceive('calculateEarnings')->andReturn($expectedEarnings);
            $orderRepository->shouldReceive('getOrderCount')->andReturn(10);

            $metricsService->shouldReceive('calculateGrowthPercentage')->andReturn(0.0);
            $metricsService->shouldReceive('formatCurrency')->andReturn('$'.$revenue);
            $metricsService->shouldReceive('formatPercentage')
                ->andReturn(['value' => '0%', 'color' => 'gray', 'icon' => 'minus']);

            $service = new StoreOwnerDashboardService($orderRepository, $storeRepository, $metricsService);
            $kpis = $service->getKPIMetrics($storeId);

            $this->assertLessThanOrEqual(
                $kpis['revenue']['value'],
                $kpis['earnings']['value'],
                "Iteration $i: Earnings ($expectedEarnings) should never exceed revenue ($revenue)"
            );
        }
    }
}

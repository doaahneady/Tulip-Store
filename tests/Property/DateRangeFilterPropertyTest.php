<?php

namespace Tests\Property;

use App\Models\Order;
use App\Services\Dashboard\DataTableService;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Property-Based Tests for Date Range Filter Correctness
 *
 * **Feature: dashboard-system-rebuild, Property 10: Date Range Filter Correctness**
 * **Validates: Requirements 4.4**
 *
 * These tests verify that date range filtering returns only records
 * within the specified date range inclusive of boundaries.
 */
class DateRangeFilterPropertyTest extends TestCase
{
    protected DataTableService $dataTableService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create orders table if it doesn't exist
        if (! Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number');
                $table->foreignId('user_id')->nullable();
                $table->string('status')->default('pending');
                $table->decimal('total', 12, 2)->default(0);
                $table->timestamps();
            });
        }

        $this->dataTableService = new DataTableService;
    }

    protected function tearDown(): void
    {
        // Clean up data after each test
        if (Schema::hasTable('orders')) {
            \DB::table('orders')->truncate();
        }

        parent::tearDown();
    }

    /**
     * Generate orders with random dates within a range
     */
    protected function generateOrdersWithDates(int $count, Carbon $minDate, Carbon $maxDate): void
    {
        $orders = [];
        $daysDiff = $minDate->diffInDays($maxDate);

        for ($i = 0; $i < $count; $i++) {
            $randomDays = rand(0, $daysDiff);
            $orderDate = $minDate->copy()->addDays($randomDays);

            $orders[] = [
                'order_number' => 'ORD-'.str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'user_id' => null,
                'status' => 'pending',
                'total' => rand(100, 10000) / 100,
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ];
        }

        if (! empty($orders)) {
            foreach (array_chunk($orders, 100) as $chunk) {
                \DB::table('orders')->insert($chunk);
            }
        }
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 10: Date Range Filter Correctness**
     * **Validates: Requirements 4.4**
     *
     * *For any* date range [start, end] and *for any* dataset, all returned records
     * SHALL have their date field >= start AND <= end.
     *
     * @test
     */
    public function property_date_range_filter_returns_only_records_within_range(): void
    {
        // Run 100 iterations with random data
        for ($iteration = 0; $iteration < 100; $iteration++) {
            // Clean up from previous iteration
            \DB::table('orders')->truncate();

            // Generate a date range for the dataset (last 90 days)
            $datasetStart = Carbon::now()->subDays(90);
            $datasetEnd = Carbon::now();

            // Generate random dataset size (20-50 records)
            $datasetSize = rand(20, 50);
            $this->generateOrdersWithDates($datasetSize, $datasetStart, $datasetEnd);

            // Generate random filter date range (subset of dataset range)
            $filterDaysFromStart = rand(10, 40);
            $filterDaysToEnd = rand(10, 40);

            $filterStart = $datasetStart->copy()->addDays($filterDaysFromStart);
            $filterEnd = $datasetEnd->copy()->subDays($filterDaysToEnd);

            // Ensure filterStart is before filterEnd
            if ($filterStart->gt($filterEnd)) {
                $temp = $filterStart;
                $filterStart = $filterEnd;
                $filterEnd = $temp;
            }

            // Reset page
            request()->merge(['page' => 1]);

            // Get filtered results using DataTableService
            $query = Order::query();
            $result = $this->dataTableService->apply($query, [
                'date_from' => $filterStart,
                'date_to' => $filterEnd,
                'date_column' => 'created_at',
                'per_page' => 100,
            ]);

            // Property: Every returned record must have created_at within [filterStart, filterEnd]
            foreach ($result->items() as $order) {
                $orderDate = Carbon::parse($order->created_at);
                $startOfDay = $filterStart->copy()->startOfDay();
                $endOfDay = $filterEnd->copy()->endOfDay();

                $this->assertTrue(
                    $orderDate->gte($startOfDay),
                    "Iteration $iteration: Order {$order->order_number} created_at ({$orderDate}) ".
                    "is before filter start date ({$startOfDay})"
                );

                $this->assertTrue(
                    $orderDate->lte($endOfDay),
                    "Iteration $iteration: Order {$order->order_number} created_at ({$orderDate}) ".
                    "is after filter end date ({$endOfDay})"
                );
            }
        }
    }

    /**
     * Test that date range filter includes boundary dates (inclusive)
     *
     * @test
     */
    public function property_date_range_includes_boundaries(): void
    {
        // Clean up
        \DB::table('orders')->truncate();

        $boundaryDate = Carbon::now()->subDays(30)->startOfDay();

        // Create an order exactly on the boundary date
        \DB::table('orders')->insert([
            'order_number' => 'ORD-BOUNDARY',
            'user_id' => null,
            'status' => 'pending',
            'total' => 100.00,
            'created_at' => $boundaryDate,
            'updated_at' => $boundaryDate,
        ]);

        // Test: Filter with start date equal to order date should include the order
        request()->merge(['page' => 1]);

        $query = Order::query();
        $result = $this->dataTableService->apply($query, [
            'date_from' => $boundaryDate,
            'date_to' => $boundaryDate->copy()->addDay(),
            'date_column' => 'created_at',
            'per_page' => 100,
        ]);

        $this->assertEquals(
            1,
            $result->total(),
            'Order on boundary start date should be included'
        );

        // Test: Filter with end date equal to order date should include the order
        $query = Order::query();
        $result = $this->dataTableService->apply($query, [
            'date_from' => $boundaryDate->copy()->subDay(),
            'date_to' => $boundaryDate,
            'date_column' => 'created_at',
            'per_page' => 100,
        ]);

        $this->assertEquals(
            1,
            $result->total(),
            'Order on boundary end date should be included'
        );
    }

    /**
     * Test that only date_from filter works correctly
     *
     * @test
     */
    public function property_date_from_only_filter(): void
    {
        // Clean up
        \DB::table('orders')->truncate();

        // Create orders at different dates
        $dates = [
            Carbon::now()->subDays(60),
            Carbon::now()->subDays(30),
            Carbon::now()->subDays(10),
            Carbon::now(),
        ];

        foreach ($dates as $i => $date) {
            \DB::table('orders')->insert([
                'order_number' => 'ORD-'.($i + 1),
                'user_id' => null,
                'status' => 'pending',
                'total' => 100.00,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        // Filter from 30 days ago (should return 3 orders)
        $filterFrom = Carbon::now()->subDays(30);

        request()->merge(['page' => 1]);

        $query = Order::query();
        $result = $this->dataTableService->apply($query, [
            'date_from' => $filterFrom,
            'date_column' => 'created_at',
            'per_page' => 100,
        ]);

        $this->assertEquals(
            3,
            $result->total(),
            'Filter from 30 days ago should return 3 orders'
        );

        // Verify all returned orders are >= filter date
        foreach ($result->items() as $order) {
            $orderDate = Carbon::parse($order->created_at);
            $this->assertTrue(
                $orderDate->gte($filterFrom->startOfDay()),
                "Order {$order->order_number} should be on or after filter date"
            );
        }
    }

    /**
     * Test that only date_to filter works correctly
     *
     * @test
     */
    public function property_date_to_only_filter(): void
    {
        // Clean up
        \DB::table('orders')->truncate();

        // Create orders at different dates
        $dates = [
            Carbon::now()->subDays(60),
            Carbon::now()->subDays(30),
            Carbon::now()->subDays(10),
            Carbon::now(),
        ];

        foreach ($dates as $i => $date) {
            \DB::table('orders')->insert([
                'order_number' => 'ORD-'.($i + 1),
                'user_id' => null,
                'status' => 'pending',
                'total' => 100.00,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        // Filter to 30 days ago (should return 2 orders)
        $filterTo = Carbon::now()->subDays(30);

        request()->merge(['page' => 1]);

        $query = Order::query();
        $result = $this->dataTableService->apply($query, [
            'date_to' => $filterTo,
            'date_column' => 'created_at',
            'per_page' => 100,
        ]);

        $this->assertEquals(
            2,
            $result->total(),
            'Filter to 30 days ago should return 2 orders'
        );

        // Verify all returned orders are <= filter date
        foreach ($result->items() as $order) {
            $orderDate = Carbon::parse($order->created_at);
            $this->assertTrue(
                $orderDate->lte($filterTo->endOfDay()),
                "Order {$order->order_number} should be on or before filter date"
            );
        }
    }

    /**
     * Test that no date filter returns all records
     *
     * @test
     */
    public function property_no_date_filter_returns_all(): void
    {
        // Clean up
        \DB::table('orders')->truncate();

        // Create orders at different dates
        $datasetSize = 25;
        $this->generateOrdersWithDates($datasetSize, Carbon::now()->subDays(90), Carbon::now());

        request()->merge(['page' => 1]);

        $query = Order::query();
        $result = $this->dataTableService->apply($query, [
            'per_page' => 100,
        ]);

        $this->assertEquals(
            $datasetSize,
            $result->total(),
            "No date filter should return all $datasetSize records"
        );
    }
}

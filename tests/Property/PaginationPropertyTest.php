<?php

namespace Tests\Property;

use App\Models\User;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Property-Based Tests for Pagination
 *
 * These tests verify the correctness properties of pagination
 * by running multiple iterations with randomly generated test data.
 */
class PaginationPropertyTest extends TestCase
{
    protected UserRepository $userRepository;

    /**
     * Valid page sizes as per Requirements 4.1
     */
    protected array $validPageSizes = [10, 25, 50, 100];

    protected function setUp(): void
    {
        parent::setUp();

        // Create users table if it doesn't exist
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('phone')->nullable();
                $table->string('mobile')->nullable();
                $table->string('user_full_name')->nullable();
                $table->boolean('verified')->default(false);
                $table->boolean('is_admin')->default(false);
                $table->boolean('is_trader')->default(false);
                $table->boolean('is_it')->default(false);
                $table->boolean('is_hr')->default(false);
                $table->boolean('is_cs')->default(false);
                $table->boolean('is_finance')->default(false);
                $table->boolean('is_accountant')->default(false);
                $table->boolean('is_driver_supervisor')->default(false);
                $table->timestamps();
            });
        }

        $this->userRepository = new UserRepository(new User);
    }

    protected function tearDown(): void
    {
        // Clean up data after each test
        if (Schema::hasTable('users')) {
            \DB::table('users')->truncate();
        }

        parent::tearDown();
    }

    /**
     * Generate random users for testing using batch insert for efficiency
     */
    protected function generateRandomUsers(int $count): void
    {
        $users = [];
        $timestamp = now();

        for ($i = 0; $i < $count; $i++) {
            $users[] = [
                'name' => 'Test User '.$i,
                'email' => 'testuser'.$i.'_'.uniqid().'@example.com',
                'password' => 'hashed_password',
                'phone' => '123456789'.$i,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        // Batch insert for efficiency
        if (! empty($users)) {
            foreach (array_chunk($users, 100) as $chunk) {
                \DB::table('users')->insert($chunk);
            }
        }
    }

    /**
     * Get a random valid page size
     */
    protected function getRandomValidPageSize(): int
    {
        return $this->validPageSizes[array_rand($this->validPageSizes)];
    }

    /**
     * Get a random dataset size (between 0 and 150 records)
     */
    protected function getRandomDatasetSize(): int
    {
        return rand(0, 150);
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 7: Pagination Bounds**
     * **Validates: Requirements 4.1**
     *
     * *For any* dataset and *for any* valid page size (10, 25, 50, 100),
     * the returned page SHALL contain at most `pageSize` items.
     *
     * @test
     */
    public function property_pagination_bounds_respects_page_size(): void
    {
        // Run 100 iterations with random data
        for ($i = 0; $i < 100; $i++) {
            // Clean up from previous iteration
            \DB::table('users')->truncate();

            // Generate random dataset size
            $datasetSize = $this->getRandomDatasetSize();

            // Generate random users
            $this->generateRandomUsers($datasetSize);

            // Pick a random valid page size
            $pageSize = $this->getRandomValidPageSize();

            // Reset page
            request()->merge(['page' => 1]);

            // Get paginated results
            $result = $this->userRepository->getAll(['per_page' => $pageSize]);

            // Property: The number of items on the current page should be at most pageSize
            $itemsOnPage = $result->count();

            $this->assertLessThanOrEqual(
                $pageSize,
                $itemsOnPage,
                "Iteration $i: Page should contain at most $pageSize items, but got $itemsOnPage ".
                "(dataset size: $datasetSize, page size: $pageSize)"
            );

            // Additional property: If dataset has more items than page size,
            // the page should contain exactly pageSize items (for first page)
            if ($datasetSize > $pageSize) {
                $this->assertEquals(
                    $pageSize,
                    $itemsOnPage,
                    "Iteration $i: First page should contain exactly $pageSize items when dataset ($datasetSize) > page size"
                );
            }

            // Additional property: If dataset has fewer items than page size,
            // the page should contain all items
            if ($datasetSize <= $pageSize && $datasetSize > 0) {
                $this->assertEquals(
                    $datasetSize,
                    $itemsOnPage,
                    "Iteration $i: Page should contain all $datasetSize items when dataset <= page size ($pageSize)"
                );
            }

            // Property: Total count should match dataset size
            $this->assertEquals(
                $datasetSize,
                $result->total(),
                "Iteration $i: Total count should match dataset size"
            );
        }
    }

    /**
     * Additional test: Verify all valid page sizes work correctly
     *
     * @test
     */
    public function property_all_valid_page_sizes_work(): void
    {
        // Clean up
        \DB::table('users')->truncate();

        // Create a fixed dataset larger than the largest page size
        $datasetSize = 150;
        $this->generateRandomUsers($datasetSize);

        // Test each valid page size
        foreach ($this->validPageSizes as $pageSize) {
            // Reset page
            request()->merge(['page' => 1]);

            $result = $this->userRepository->getAll(['per_page' => $pageSize]);

            // Property: First page should have exactly pageSize items
            $this->assertEquals(
                $pageSize,
                $result->count(),
                "Page size $pageSize: First page should have exactly $pageSize items"
            );

            // Property: Total should match dataset size
            $this->assertEquals(
                $datasetSize,
                $result->total(),
                "Page size $pageSize: Total should match dataset size"
            );

            // Property: Last page should be calculated correctly
            $expectedLastPage = (int) ceil($datasetSize / $pageSize);
            $this->assertEquals(
                $expectedLastPage,
                $result->lastPage(),
                "Page size $pageSize: Last page should be $expectedLastPage"
            );
        }
    }
}

<?php

namespace Tests\Property;

use App\Models\User;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

/**
 * Property-Based Tests for Sort Order Correctness
 * 
 * **Feature: dashboard-system-rebuild, Property 8: Sort Order Correctness**
 * **Validates: Requirements 4.2**
 * 
 * These tests verify that sorting produces results where each item's sort value 
 * is correctly ordered relative to the next item's sort value.
 */
class SortOrderPropertyTest extends TestCase
{
    protected UserRepository $userRepository;

    /**
     * Sortable columns for testing
     */
    protected array $sortableColumns = ['name', 'email', 'created_at'];

    /**
     * Sort directions
     */
    protected array $sortDirections = ['asc', 'desc'];

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create users table if it doesn't exist
        if (!Schema::hasTable('users')) {
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
        
        $this->userRepository = new UserRepository(new User());
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
        $baseTime = now();
        $firstNames = ['Alice', 'Bob', 'Charlie', 'Diana', 'Eve', 'Frank', 'Grace', 'Henry', 'Ivy', 'Jack'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis'];
        
        for ($i = 0; $i < $count; $i++) {
            // Generate varied timestamps to ensure sorting differences
            $createdAt = $baseTime->copy()->subMinutes(rand(0, 10000));
            $updatedAt = $createdAt->copy()->addMinutes(rand(0, 100));
            
            $users[] = [
                'name' => $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)],
                'email' => 'user' . $i . '_' . uniqid() . '@example.com',
                'password' => 'hashed_password',
                'phone' => '123456789' . $i,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];
        }
        
        // Batch insert for efficiency
        if (!empty($users)) {
            foreach (array_chunk($users, 100) as $chunk) {
                \DB::table('users')->insert($chunk);
            }
        }
    }

    /**
     * Compare two values for sort order correctness
     */
    protected function compareSortValues($value1, $value2, string $direction): bool
    {
        // Handle datetime comparison
        if ($value1 instanceof \Carbon\Carbon || $value1 instanceof \DateTime) {
            $value1 = $value1->timestamp;
        }
        if ($value2 instanceof \Carbon\Carbon || $value2 instanceof \DateTime) {
            $value2 = $value2->timestamp;
        }

        if ($direction === 'asc') {
            return $value1 <= $value2;
        } else {
            return $value1 >= $value2;
        }
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 8: Sort Order Correctness**
     * **Validates: Requirements 4.2**
     * 
     * *For any* dataset and *for any* sortable column, sorting in ascending order 
     * SHALL produce results where each item's sort value is less than or equal to 
     * the next item's sort value.
     * 
     * @test
     */
    public function property_sort_order_correctness(): void
    {
        // Run 50 iterations with random data
        for ($iteration = 0; $iteration < 50; $iteration++) {
            // Clean up from previous iteration
            \DB::table('users')->truncate();
            
            // Generate random dataset size (5-20 records)
            $datasetSize = rand(5, 20);
            $this->generateRandomUsers($datasetSize);
            
            // Pick a random sortable column and direction
            $sortColumn = $this->sortableColumns[array_rand($this->sortableColumns)];
            $sortDirection = $this->sortDirections[array_rand($this->sortDirections)];
            
            // Reset page
            request()->merge(['page' => 1]);
            
            // Get sorted results
            $result = $this->userRepository->getAll([
                'sort_by' => $sortColumn,
                'sort_direction' => $sortDirection,
                'per_page' => 100,
            ]);
            
            $items = $result->items();
            
            // Property: Each item's sort value should be <= (asc) or >= (desc) the next item's value
            for ($j = 0; $j < count($items) - 1; $j++) {
                $currentValue = $items[$j]->$sortColumn;
                $nextValue = $items[$j + 1]->$sortColumn;
                
                $isCorrectOrder = $this->compareSortValues($currentValue, $nextValue, $sortDirection);
                
                $this->assertTrue(
                    $isCorrectOrder,
                    "Iteration $iteration: Sort order violated at position $j. " .
                    "Column: $sortColumn, Direction: $sortDirection."
                );
            }
        }
    }

    /**
     * Test ascending sort produces correct order for all columns
     * 
     * @test
     */
    public function property_ascending_sort_produces_correct_order(): void
    {
        // Clean up
        \DB::table('users')->truncate();
        
        // Create a fixed dataset
        $this->generateRandomUsers(20);
        
        // Test each sortable column in ascending order
        foreach ($this->sortableColumns as $column) {
            request()->merge(['page' => 1]);
            
            $result = $this->userRepository->getAll([
                'sort_by' => $column,
                'sort_direction' => 'asc',
                'per_page' => 100,
            ]);
            
            $items = $result->items();
            
            for ($j = 0; $j < count($items) - 1; $j++) {
                $isCorrectOrder = $this->compareSortValues(
                    $items[$j]->$column, 
                    $items[$j + 1]->$column, 
                    'asc'
                );
                
                $this->assertTrue(
                    $isCorrectOrder,
                    "Ascending sort on '$column' violated at position $j"
                );
            }
        }
    }

    /**
     * Test descending sort produces correct order for all columns
     * 
     * @test
     */
    public function property_descending_sort_produces_correct_order(): void
    {
        // Clean up
        \DB::table('users')->truncate();
        
        // Create a fixed dataset
        $this->generateRandomUsers(20);
        
        // Test each sortable column in descending order
        foreach ($this->sortableColumns as $column) {
            request()->merge(['page' => 1]);
            
            $result = $this->userRepository->getAll([
                'sort_by' => $column,
                'sort_direction' => 'desc',
                'per_page' => 100,
            ]);
            
            $items = $result->items();
            
            for ($j = 0; $j < count($items) - 1; $j++) {
                $isCorrectOrder = $this->compareSortValues(
                    $items[$j]->$column, 
                    $items[$j + 1]->$column, 
                    'desc'
                );
                
                $this->assertTrue(
                    $isCorrectOrder,
                    "Descending sort on '$column' violated at position $j"
                );
            }
        }
    }
}

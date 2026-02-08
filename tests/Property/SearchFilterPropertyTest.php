<?php

namespace Tests\Property;

use App\Models\User;
use App\Services\Dashboard\DataTableService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Property-Based Tests for Search Filter Correctness
 *
 * **Feature: dashboard-system-rebuild, Property 9: Search Filter Correctness**
 * **Validates: Requirements 4.3**
 *
 * These tests verify that search filtering returns only results that contain
 * the search term in at least one searchable column.
 */
class SearchFilterPropertyTest extends TestCase
{
    protected DataTableService $dataTableService;

    /**
     * Searchable columns for testing
     */
    protected array $searchableColumns = ['name', 'email', 'phone'];

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

        $this->dataTableService = new DataTableService;
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
        $firstNames = ['Alice', 'Bob', 'Charlie', 'Diana', 'Eve', 'Frank', 'Grace', 'Henry', 'Ivy', 'Jack'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis'];
        $domains = ['example.com', 'test.org', 'demo.net', 'sample.io'];

        for ($i = 0; $i < $count; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $domain = $domains[array_rand($domains)];

            $users[] = [
                'name' => $firstName.' '.$lastName,
                'email' => strtolower($firstName).'.'.strtolower($lastName).$i.'@'.$domain,
                'password' => 'hashed_password',
                'phone' => '555'.str_pad($i, 7, '0', STR_PAD_LEFT),
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
     * Generate a random search term from existing data
     */
    protected function getRandomSearchTerm(): string
    {
        $terms = [
            'alice', 'bob', 'charlie', 'diana', 'eve', 'frank', 'grace', 'henry', 'ivy', 'jack',
            'smith', 'johnson', 'williams', 'brown', 'jones', 'garcia', 'miller', 'davis',
            'example', 'test', 'demo', 'sample',
            '555', '@',
        ];

        return $terms[array_rand($terms)];
    }

    /**
     * Check if a user matches the search term in any searchable column
     */
    protected function userMatchesSearch(User $user, string $searchTerm): bool
    {
        $searchTerm = strtolower($searchTerm);

        foreach ($this->searchableColumns as $column) {
            $value = $user->$column ?? null;
            if ($value !== null && str_contains(strtolower((string) $value), $searchTerm)) {
                return true;
            }
        }

        return false;
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 9: Search Filter Correctness**
     * **Validates: Requirements 4.3**
     *
     * *For any* search term and *for any* dataset, all returned results
     * SHALL contain the search term in at least one searchable column.
     *
     * @test
     */
    public function property_search_filter_returns_only_matching_results(): void
    {
        // Run 100 iterations with random data
        for ($iteration = 0; $iteration < 100; $iteration++) {
            // Clean up from previous iteration
            \DB::table('users')->truncate();

            // Generate random dataset size (10-50 records)
            $datasetSize = rand(10, 50);
            $this->generateRandomUsers($datasetSize);

            // Pick a random search term
            $searchTerm = $this->getRandomSearchTerm();

            // Reset page
            request()->merge(['page' => 1]);

            // Get filtered results using DataTableService
            $query = User::query();
            $result = $this->dataTableService->apply($query, [
                'search' => $searchTerm,
                'searchable_columns' => $this->searchableColumns,
                'per_page' => 100,
            ]);

            // Property: Every returned result must contain the search term in at least one searchable column
            foreach ($result->items() as $user) {
                $matches = $this->userMatchesSearch($user, $searchTerm);

                $this->assertTrue(
                    $matches,
                    "Iteration $iteration: User ID {$user->id} does not contain search term '$searchTerm' ".
                    "in any searchable column. Name: {$user->name}, Email: {$user->email}, Phone: {$user->phone}"
                );
            }
        }
    }

    /**
     * Test that empty search returns all results
     *
     * @test
     */
    public function property_empty_search_returns_all_results(): void
    {
        // Clean up
        \DB::table('users')->truncate();

        // Create a fixed dataset
        $datasetSize = 25;
        $this->generateRandomUsers($datasetSize);

        // Test with empty search term
        request()->merge(['page' => 1]);

        $query = User::query();
        $result = $this->dataTableService->apply($query, [
            'search' => '',
            'searchable_columns' => $this->searchableColumns,
            'per_page' => 100,
        ]);

        // Property: Empty search should return all records
        $this->assertEquals(
            $datasetSize,
            $result->total(),
            "Empty search should return all $datasetSize records"
        );
    }

    /**
     * Test that search is case-insensitive
     *
     * @test
     */
    public function property_search_is_case_insensitive(): void
    {
        // Clean up
        \DB::table('users')->truncate();

        // Create a user with known data
        \DB::table('users')->insert([
            'name' => 'Alice Smith',
            'email' => 'alice.smith@example.com',
            'password' => 'hashed_password',
            'phone' => '5551234567',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $searchVariants = ['alice', 'ALICE', 'Alice', 'aLiCe'];

        foreach ($searchVariants as $searchTerm) {
            request()->merge(['page' => 1]);

            $query = User::query();
            $result = $this->dataTableService->apply($query, [
                'search' => $searchTerm,
                'searchable_columns' => $this->searchableColumns,
                'per_page' => 100,
            ]);

            // Property: Search should be case-insensitive
            $this->assertEquals(
                1,
                $result->total(),
                "Search for '$searchTerm' should find the user (case-insensitive)"
            );
        }
    }

    /**
     * Test that non-matching search returns empty results
     *
     * @test
     */
    public function property_non_matching_search_returns_empty(): void
    {
        // Clean up
        \DB::table('users')->truncate();

        // Create users with known data
        $this->generateRandomUsers(20);

        // Search for something that definitely won't match
        $nonMatchingTerm = 'xyz123nonexistent456';

        request()->merge(['page' => 1]);

        $query = User::query();
        $result = $this->dataTableService->apply($query, [
            'search' => $nonMatchingTerm,
            'searchable_columns' => $this->searchableColumns,
            'per_page' => 100,
        ]);

        // Property: Non-matching search should return empty results
        $this->assertEquals(
            0,
            $result->total(),
            "Search for '$nonMatchingTerm' should return no results"
        );
    }
}

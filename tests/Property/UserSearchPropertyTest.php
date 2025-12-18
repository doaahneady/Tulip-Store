<?php

namespace Tests\Property;

use App\Models\User;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

/**
 * Property-Based Tests for User Search Correctness
 * 
 * **Feature: dashboard-system-rebuild, Property 17: User Search Correctness**
 * **Validates: Requirements 7.3**
 * 
 * These tests verify that user search returns only users whose name, email, 
 * or phone fields contain the search query.
 */
class UserSearchPropertyTest extends TestCase
{
    protected UserRepository $userRepository;

    /**
     * Searchable columns for user search
     */
    protected array $searchableColumns = ['name', 'email', 'phone', 'mobile', 'user_full_name'];

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
                $table->boolean('is_it_super')->default(false);
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
        $timestamp = now();
        $firstNames = ['Alice', 'Bob', 'Charlie', 'Diana', 'Eve', 'Frank', 'Grace', 'Henry', 'Ivy', 'Jack', 
                       'Kate', 'Leo', 'Mia', 'Noah', 'Olivia', 'Peter', 'Quinn', 'Rose', 'Sam', 'Tina'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis',
                      'Martinez', 'Anderson', 'Taylor', 'Thomas', 'Moore', 'Jackson', 'Martin'];
        $domains = ['example.com', 'test.org', 'demo.net', 'sample.io', 'mail.com'];
        
        for ($i = 0; $i < $count; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $domain = $domains[array_rand($domains)];
            
            $users[] = [
                'name' => $firstName . ' ' . $lastName,
                'email' => strtolower($firstName) . '.' . strtolower($lastName) . $i . '@' . $domain,
                'password' => 'hashed_password',
                'phone' => '555' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'mobile' => rand(0, 1) ? '555' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT) : null,
                'user_full_name' => rand(0, 1) ? $firstName . ' ' . chr(rand(65, 90)) . '. ' . $lastName : null,
                'verified' => rand(0, 1),
                'is_admin' => rand(0, 10) === 0,
                'is_trader' => rand(0, 5) === 0,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
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
     * Generate a random search term from existing data
     */
    protected function getRandomSearchTerm(): string
    {
        $terms = [
            // First names
            'alice', 'bob', 'charlie', 'diana', 'eve', 'frank', 'grace', 'henry', 'ivy', 'jack',
            'kate', 'leo', 'mia', 'noah', 'olivia', 'peter', 'quinn', 'rose', 'sam', 'tina',
            // Last names
            'smith', 'johnson', 'williams', 'brown', 'jones', 'garcia', 'miller', 'davis',
            'martinez', 'anderson', 'taylor', 'thomas', 'moore', 'jackson', 'martin',
            // Email domains
            'example', 'test', 'demo', 'sample', 'mail',
            // Phone patterns
            '555',
            // Email patterns
            '@',
        ];
        
        return $terms[array_rand($terms)];
    }

    /**
     * Check if a user matches the search term in name, email, or phone
     */
    protected function userMatchesSearch(User $user, string $searchTerm): bool
    {
        $searchTerm = strtolower($searchTerm);
        
        // Check name
        if ($user->name && str_contains(strtolower($user->name), $searchTerm)) {
            return true;
        }
        
        // Check email
        if ($user->email && str_contains(strtolower($user->email), $searchTerm)) {
            return true;
        }
        
        // Check phone
        if ($user->phone && str_contains(strtolower($user->phone), $searchTerm)) {
            return true;
        }
        
        // Check mobile
        if ($user->mobile && str_contains(strtolower($user->mobile), $searchTerm)) {
            return true;
        }
        
        // Check user_full_name
        if ($user->user_full_name && str_contains(strtolower($user->user_full_name), $searchTerm)) {
            return true;
        }
        
        return false;
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 17: User Search Correctness**
     * **Validates: Requirements 7.3**
     * 
     * *For any* search query, all returned users SHALL have the query string 
     * present in their name, email, or phone fields.
     * 
     * @test
     */
    public function property_user_search_returns_only_matching_users(): void
    {
        // Run 100 iterations with random data
        for ($iteration = 0; $iteration < 100; $iteration++) {
            // Clean up from previous iteration
            \DB::table('users')->truncate();
            
            // Generate random dataset size (20-100 records)
            $datasetSize = rand(20, 100);
            $this->generateRandomUsers($datasetSize);
            
            // Pick a random search term
            $searchTerm = $this->getRandomSearchTerm();
            
            // Get search results using UserRepository
            $results = $this->userRepository->search($searchTerm, ['per_page' => 200]);
            
            // Property: Every returned user must contain the search term in name, email, or phone
            foreach ($results->items() as $user) {
                $matches = $this->userMatchesSearch($user, $searchTerm);
                
                $this->assertTrue(
                    $matches,
                    "Iteration $iteration: User ID {$user->id} does not contain search term '$searchTerm' " .
                    "in name, email, or phone. Name: {$user->name}, Email: {$user->email}, " .
                    "Phone: {$user->phone}, Mobile: {$user->mobile}, Full Name: {$user->user_full_name}"
                );
            }
        }
    }


    /**
     * Test that search results are returned within acceptable time (500ms)
     * 
     * @test
     */
    public function property_user_search_completes_within_time_limit(): void
    {
        // Clean up
        \DB::table('users')->truncate();
        
        // Create a larger dataset to test performance
        $this->generateRandomUsers(200);
        
        // Run multiple search iterations
        for ($iteration = 0; $iteration < 20; $iteration++) {
            $searchTerm = $this->getRandomSearchTerm();
            
            $startTime = microtime(true);
            $results = $this->userRepository->search($searchTerm, ['per_page' => 100]);
            $endTime = microtime(true);
            
            $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
            
            // Property: Search should complete within 500ms (Requirement 7.3)
            $this->assertLessThan(
                500,
                $executionTime,
                "Iteration $iteration: Search for '$searchTerm' took {$executionTime}ms, exceeding 500ms limit"
            );
        }
    }

    /**
     * Test that empty search returns all users
     * 
     * @test
     */
    public function property_empty_search_returns_all_users(): void
    {
        // Clean up
        \DB::table('users')->truncate();
        
        // Create a fixed dataset
        $datasetSize = 30;
        $this->generateRandomUsers($datasetSize);
        
        // Search with empty string - should return all users
        $results = $this->userRepository->search('', ['per_page' => 100]);
        
        // Property: Empty search should return all records
        $this->assertEquals(
            $datasetSize,
            $results->total(),
            "Empty search should return all $datasetSize users"
        );
    }

    /**
     * Test that search is case-insensitive
     * 
     * @test
     */
    public function property_user_search_is_case_insensitive(): void
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
        
        $searchVariants = ['alice', 'ALICE', 'Alice', 'aLiCe', 'SMITH', 'smith', 'Smith'];
        
        foreach ($searchVariants as $searchTerm) {
            $results = $this->userRepository->search($searchTerm, ['per_page' => 100]);
            
            // Property: Search should be case-insensitive
            $this->assertEquals(
                1,
                $results->total(),
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
        $this->generateRandomUsers(30);
        
        // Search for something that definitely won't match
        $nonMatchingTerms = ['xyz123nonexistent456', 'qwertyuiop987654', 'zzzzzzzzzz'];
        
        foreach ($nonMatchingTerms as $searchTerm) {
            $results = $this->userRepository->search($searchTerm, ['per_page' => 100]);
            
            // Property: Non-matching search should return empty results
            $this->assertEquals(
                0,
                $results->total(),
                "Search for '$searchTerm' should return no results"
            );
        }
    }

    /**
     * Test that search works correctly with filters
     * 
     * @test
     */
    public function property_user_search_respects_filters(): void
    {
        // Clean up
        \DB::table('users')->truncate();
        
        // Create users with specific roles - insert separately to avoid column mismatch
        \DB::table('users')->insert([
            'name' => 'Admin Alice',
            'email' => 'admin.alice@example.com',
            'password' => 'hashed_password',
            'phone' => '5551111111',
            'is_admin' => true,
            'is_trader' => false,
            'verified' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        \DB::table('users')->insert([
            'name' => 'Trader Alice',
            'email' => 'trader.alice@example.com',
            'password' => 'hashed_password',
            'phone' => '5552222222',
            'is_admin' => false,
            'is_trader' => true,
            'verified' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        \DB::table('users')->insert([
            'name' => 'Regular Alice',
            'email' => 'regular.alice@example.com',
            'password' => 'hashed_password',
            'phone' => '5553333333',
            'is_admin' => false,
            'is_trader' => false,
            'verified' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Search for 'alice' with admin role filter
        $results = $this->userRepository->search('alice', ['role' => 'admin', 'per_page' => 100]);
        
        // Property: Search with role filter should only return users with that role
        $this->assertEquals(1, $results->total(), "Search for 'alice' with admin filter should return 1 user");
        $this->assertTrue((bool) $results->first()->is_admin, "Returned user should be an admin");
        
        // Search for 'alice' with verified filter
        $results = $this->userRepository->search('alice', ['verified' => true, 'per_page' => 100]);
        
        // Property: Search with verified filter should only return verified users
        $this->assertEquals(2, $results->total(), "Search for 'alice' with verified filter should return 2 users");
        foreach ($results->items() as $user) {
            $this->assertTrue((bool) $user->verified, "All returned users should be verified");
        }
    }
}

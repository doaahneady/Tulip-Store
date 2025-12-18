<?php

namespace Tests\Property;

use App\Models\User;
use App\Models\Order;
use App\Models\Store;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\StoreRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\StoreRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Services\Dashboard\AdminDashboardService;
use App\Services\Dashboard\MetricsService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

/**
 * Property-Based Tests for Bulk Action Transactionality
 * 
 * **Feature: dashboard-system-rebuild, Property 18: Bulk Action Transactionality**
 * **Validates: Requirements 7.5**
 * 
 * These tests verify that bulk actions are processed transactionally,
 * rolling back all changes if any action fails.
 */
class BulkActionPropertyTest extends TestCase
{
    protected AdminDashboardService $adminService;
    protected UserRepository $userRepository;
    protected OrderRepository $orderRepository;
    protected StoreRepository $storeRepository;

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
                $table->timestamp('email_verified_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
        
        // Create orders table if it doesn't exist
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->nullable();
                $table->foreignId('user_id')->nullable();
                $table->string('recipient_name')->nullable();
                $table->string('phone')->nullable();
                $table->decimal('total', 10, 2)->default(0);
                $table->string('status')->default('pending');
                $table->string('payment_status')->default('pending');
                $table->timestamps();
            });
        }
        
        // Create order_items table if it doesn't exist (required for Order relationships)
        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id');
                $table->foreignId('product_id')->nullable();
                $table->string('product_name')->nullable();
                $table->integer('quantity')->default(1);
                $table->decimal('price', 10, 2)->default(0);
                $table->timestamps();
            });
        }
        
        // Create stores table if it doesn't exist
        if (!Schema::hasTable('stores')) {
            Schema::create('stores', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable();
                $table->string('name');
                $table->string('slug')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('status')->default('pending');
                $table->decimal('commission_rate', 5, 2)->default(10);
                $table->decimal('total_sales', 12, 2)->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }
        
        // Initialize repositories
        $this->userRepository = new UserRepository(new User());
        $this->orderRepository = new OrderRepository(new Order());
        $this->storeRepository = new StoreRepository(new Store());
        
        // Bind repositories to container
        $this->app->instance(UserRepositoryInterface::class, $this->userRepository);
        $this->app->instance(OrderRepositoryInterface::class, $this->orderRepository);
        $this->app->instance(StoreRepositoryInterface::class, $this->storeRepository);
        
        // Create MetricsService
        $metricsService = new MetricsService($this->orderRepository);
        
        // Create AdminDashboardService
        $this->adminService = new AdminDashboardService(
            $this->userRepository,
            $this->orderRepository,
            $this->storeRepository,
            $metricsService
        );
    }

    protected function tearDown(): void
    {
        // Clean up data after each test
        if (Schema::hasTable('users')) {
            \DB::table('users')->truncate();
        }
        if (Schema::hasTable('orders')) {
            \DB::table('orders')->truncate();
        }
        if (Schema::hasTable('order_items')) {
            \DB::table('order_items')->truncate();
        }
        if (Schema::hasTable('stores')) {
            \DB::table('stores')->truncate();
        }
        
        parent::tearDown();
    }

    /**
     * Generate random users for testing
     */
    protected function generateRandomUsers(int $count): array
    {
        $userIds = [];
        $timestamp = now();
        
        for ($i = 0; $i < $count; $i++) {
            $id = \DB::table('users')->insertGetId([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => 'hashed_password',
                'phone' => '555' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'verified' => false,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
            $userIds[] = $id;
        }
        
        return $userIds;
    }

    /**
     * Generate random orders for testing
     */
    protected function generateRandomOrders(int $count): array
    {
        $orderIds = [];
        $timestamp = now();
        
        for ($i = 0; $i < $count; $i++) {
            $id = \DB::table('orders')->insertGetId([
                'order_number' => 'ORD-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'recipient_name' => 'Customer ' . $i,
                'phone' => '555' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'total' => rand(10, 500),
                'status' => 'pending',
                'payment_status' => 'pending',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
            $orderIds[] = $id;
        }
        
        return $orderIds;
    }

    /**
     * Generate random stores for testing
     */
    protected function generateRandomStores(int $count): array
    {
        $storeIds = [];
        $timestamp = now();
        
        for ($i = 0; $i < $count; $i++) {
            $id = \DB::table('stores')->insertGetId([
                'name' => 'Store ' . $i,
                'slug' => 'store-' . $i,
                'email' => 'store' . $i . '@example.com',
                'phone' => '555' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'status' => 'pending',
                'commission_rate' => 10,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
            $storeIds[] = $id;
        }
        
        return $storeIds;
    }


    /**
     * **Feature: dashboard-system-rebuild, Property 18: Bulk Action Transactionality**
     * **Validates: Requirements 7.5**
     * 
     * *For any* bulk action that fails on any item, no changes SHALL be 
     * persisted to the database (full rollback).
     * 
     * @test
     */
    public function property_bulk_user_action_rolls_back_on_failure(): void
    {
        // Run 50 iterations with random data
        for ($iteration = 0; $iteration < 50; $iteration++) {
            // Clean up from previous iteration
            \DB::table('users')->truncate();
            
            // Generate random users (5-15)
            $userCount = rand(5, 15);
            $userIds = $this->generateRandomUsers($userCount);
            
            // Record initial state
            $initialStates = [];
            foreach ($userIds as $userId) {
                $user = \DB::table('users')->find($userId);
                $initialStates[$userId] = [
                    'verified' => $user->verified,
                    'email_verified_at' => $user->email_verified_at,
                ];
            }
            
            // Add a non-existent user ID to cause failure
            $invalidIds = array_merge($userIds, [999999]);
            
            // Attempt bulk action that will fail
            $result = $this->adminService->processBulkUserAction('activate', $invalidIds);
            
            // Property: Action should fail
            $this->assertFalse(
                $result['success'],
                "Iteration $iteration: Bulk action with invalid ID should fail"
            );
            
            // Property: No changes should be persisted (full rollback)
            foreach ($userIds as $userId) {
                $user = \DB::table('users')->find($userId);
                
                $this->assertEquals(
                    $initialStates[$userId]['verified'],
                    $user->verified,
                    "Iteration $iteration: User $userId verified status should be unchanged after rollback"
                );
            }
        }
    }

    /**
     * Test that successful bulk user actions persist all changes
     * 
     * @test
     */
    public function property_bulk_user_action_persists_all_on_success(): void
    {
        // Run 50 iterations with random data
        for ($iteration = 0; $iteration < 50; $iteration++) {
            // Clean up from previous iteration
            \DB::table('users')->truncate();
            
            // Generate random users (5-15)
            $userCount = rand(5, 15);
            $userIds = $this->generateRandomUsers($userCount);
            
            // Attempt bulk action with valid IDs only
            $result = $this->adminService->processBulkUserAction('activate', $userIds);
            
            // Property: Action should succeed
            $this->assertTrue(
                $result['success'],
                "Iteration $iteration: Bulk action with valid IDs should succeed"
            );
            
            // Property: All users should be activated
            foreach ($userIds as $userId) {
                $user = \DB::table('users')->find($userId);
                
                $this->assertEquals(
                    1,
                    $user->verified,
                    "Iteration $iteration: User $userId should be activated"
                );
            }
            
            // Property: Processed count should match
            $this->assertEquals(
                count($userIds),
                $result['processed'],
                "Iteration $iteration: Processed count should match user count"
            );
        }
    }

    /**
     * Test bulk order action transactionality
     * 
     * @test
     */
    public function property_bulk_order_action_rolls_back_on_failure(): void
    {
        // Run 50 iterations with random data
        for ($iteration = 0; $iteration < 50; $iteration++) {
            // Clean up from previous iteration
            \DB::table('orders')->truncate();
            
            // Generate random orders (5-15)
            $orderCount = rand(5, 15);
            $orderIds = $this->generateRandomOrders($orderCount);
            
            // Record initial state
            $initialStates = [];
            foreach ($orderIds as $orderId) {
                $order = \DB::table('orders')->find($orderId);
                $initialStates[$orderId] = $order->status;
            }
            
            // Add a non-existent order ID to cause failure
            $invalidIds = array_merge($orderIds, [999999]);
            
            // Attempt bulk action that will fail
            $result = $this->adminService->processBulkOrderAction('complete', $invalidIds);
            
            // Property: Action should fail
            $this->assertFalse(
                $result['success'],
                "Iteration $iteration: Bulk order action with invalid ID should fail"
            );
            
            // Property: No changes should be persisted (full rollback)
            foreach ($orderIds as $orderId) {
                $order = \DB::table('orders')->find($orderId);
                
                $this->assertEquals(
                    $initialStates[$orderId],
                    $order->status,
                    "Iteration $iteration: Order $orderId status should be unchanged after rollback"
                );
            }
        }
    }

    /**
     * Test successful bulk order actions persist all changes
     * 
     * @test
     */
    public function property_bulk_order_action_persists_all_on_success(): void
    {
        // Run 50 iterations with random data
        for ($iteration = 0; $iteration < 50; $iteration++) {
            // Clean up from previous iteration
            \DB::table('orders')->truncate();
            
            // Generate random orders (5-15)
            $orderCount = rand(5, 15);
            $orderIds = $this->generateRandomOrders($orderCount);
            
            // Attempt bulk action with valid IDs only
            $result = $this->adminService->processBulkOrderAction('complete', $orderIds);
            
            // Property: Action should succeed
            $this->assertTrue(
                $result['success'],
                "Iteration $iteration: Bulk order action with valid IDs should succeed. Errors: " . implode(', ', $result['errors'] ?? [])
            );
            
            // Property: All orders should be completed
            foreach ($orderIds as $orderId) {
                $order = \DB::table('orders')->find($orderId);
                
                $this->assertEquals(
                    'completed',
                    $order->status,
                    "Iteration $iteration: Order $orderId should be completed"
                );
            }
        }
    }

    /**
     * Test bulk store action transactionality
     * 
     * @test
     */
    public function property_bulk_store_action_rolls_back_on_failure(): void
    {
        // Run 50 iterations with random data
        for ($iteration = 0; $iteration < 50; $iteration++) {
            // Clean up from previous iteration
            \DB::table('stores')->truncate();
            
            // Generate random stores (5-15)
            $storeCount = rand(5, 15);
            $storeIds = $this->generateRandomStores($storeCount);
            
            // Record initial state
            $initialStates = [];
            foreach ($storeIds as $storeId) {
                $store = \DB::table('stores')->find($storeId);
                $initialStates[$storeId] = $store->status;
            }
            
            // Add a non-existent store ID to cause failure
            $invalidIds = array_merge($storeIds, [999999]);
            
            // Attempt bulk action that will fail
            $result = $this->adminService->processBulkStoreAction('approve', $invalidIds);
            
            // Property: Action should fail
            $this->assertFalse(
                $result['success'],
                "Iteration $iteration: Bulk store action with invalid ID should fail"
            );
            
            // Property: No changes should be persisted (full rollback)
            foreach ($storeIds as $storeId) {
                $store = \DB::table('stores')->find($storeId);
                
                $this->assertEquals(
                    $initialStates[$storeId],
                    $store->status,
                    "Iteration $iteration: Store $storeId status should be unchanged after rollback"
                );
            }
        }
    }

    /**
     * Test successful bulk store actions persist all changes
     * 
     * @test
     */
    public function property_bulk_store_action_persists_all_on_success(): void
    {
        // Run 50 iterations with random data
        for ($iteration = 0; $iteration < 50; $iteration++) {
            // Clean up from previous iteration
            \DB::table('stores')->truncate();
            
            // Generate random stores (5-15)
            $storeCount = rand(5, 15);
            $storeIds = $this->generateRandomStores($storeCount);
            
            // Attempt bulk action with valid IDs only
            $result = $this->adminService->processBulkStoreAction('approve', $storeIds);
            
            // Property: Action should succeed
            $this->assertTrue(
                $result['success'],
                "Iteration $iteration: Bulk store action with valid IDs should succeed"
            );
            
            // Property: All stores should be approved
            foreach ($storeIds as $storeId) {
                $store = \DB::table('stores')->find($storeId);
                
                $this->assertEquals(
                    'approved',
                    $store->status,
                    "Iteration $iteration: Store $storeId should be approved"
                );
            }
        }
    }

    /**
     * Test that unknown actions are rejected
     * 
     * @test
     */
    public function property_unknown_action_is_rejected(): void
    {
        // Clean up
        \DB::table('users')->truncate();
        
        // Generate some users
        $userIds = $this->generateRandomUsers(5);
        
        // Record initial state
        $initialStates = [];
        foreach ($userIds as $userId) {
            $user = \DB::table('users')->find($userId);
            $initialStates[$userId] = (array) $user;
        }
        
        // Attempt unknown action
        $result = $this->adminService->processBulkUserAction('unknown_action', $userIds);
        
        // Property: Action should fail
        $this->assertFalse($result['success'], "Unknown action should fail");
        
        // Property: No changes should be persisted
        foreach ($userIds as $userId) {
            $user = \DB::table('users')->find($userId);
            
            $this->assertEquals(
                $initialStates[$userId]['verified'],
                $user->verified,
                "User $userId should be unchanged after unknown action"
            );
        }
    }
}

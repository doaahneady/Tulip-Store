<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_creation_decrements_inventory_and_creates_transaction()
    {
        // 1. Setup Data
        $user = User::factory()->create();
        $this->actingAs($user);

        $storeId = DB::table('stores')->insertGetId(array_filter([
            Schema::hasColumn('stores', 'owner_id') ? 'owner_id' : 'user_id' => $user->id,
            Schema::hasColumn('stores', 'organization_id') ? 'organization_id' : null => Schema::hasColumn('stores', 'organization_id')
                ? DB::table('organizations')->insertGetId([
                    'name' => 'Org',
                    'slug' => 'org-'.Str::lower(Str::random(6)),
                    'status' => 'active',
                ])
                : null,
            'name' => 'Test Store',
            'slug' => 'test-store-'.Str::lower(Str::random(6)),
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'sku' => 'SKU-'.Str::upper(Str::random(8)),
            'store_id' => $storeId,
            'price' => 1000,
            'stock_quantity' => 10,
            'track_inventory' => true,
            'is_active' => true,
        ]);

        // 2. Simulate Cart in Session
        session(['cart' => [
            $product->id => 2,
        ]]);

        // 3. Make Request
        $response = $this->postJson('/api/orders/create', [
            'recipient_name' => 'John Doe',
            'phone' => '0912345678',
            'village' => 'Test Village',
            'location' => ['lat' => 33.5, 'lng' => 36.3],
            'delivery_method' => 'normal',
            'payment_method' => 'cash',
            'delivery_cost' => 500,
            'service_fee' => 0,
            'address_note' => 'Near the park',
        ]);

        // 4. Assertions
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $expectedOrderAttrs = [];
        if (Schema::hasColumn('orders', 'user_id')) {
            $expectedOrderAttrs['user_id'] = $user->id;
        }
        if (Schema::hasColumn('orders', 'customer_id')) {
            $expectedOrderAttrs['customer_id'] = $user->id;
        }
        if (Schema::hasColumn('orders', 'total')) {
            $expectedOrderAttrs['total'] = 2500;
        }
        if (Schema::hasColumn('orders', 'total_amount')) {
            $expectedOrderAttrs['total_amount'] = 2500;
        }
        $this->assertDatabaseHas('orders', $expectedOrderAttrs);

        // Verify Inventory Decrement
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8,
        ]);

        // Verify Financial Transaction
        $this->assertDatabaseHas('financial_transactions', [
            'user_id' => $user->id,
            'type' => 'order_payment',
            'amount' => 2500,
            'status' => 'pending',
        ]);
    }

    public function test_order_creation_fails_insufficient_stock()
    {
        // 1. Setup Data
        $user = User::factory()->create();
        $this->actingAs($user);

        $storeId = DB::table('stores')->insertGetId(array_filter([
            Schema::hasColumn('stores', 'owner_id') ? 'owner_id' : 'user_id' => $user->id,
            Schema::hasColumn('stores', 'organization_id') ? 'organization_id' : null => Schema::hasColumn('stores', 'organization_id')
                ? DB::table('organizations')->insertGetId([
                    'name' => 'Org',
                    'slug' => 'org-'.Str::lower(Str::random(6)),
                    'status' => 'active',
                ])
                : null,
            'name' => 'Test Store',
            'slug' => 'test-store-'.Str::lower(Str::random(6)),
        ], fn ($v) => $v !== null));

        $product = Product::create([
            'name' => 'Low Stock Product',
            'slug' => 'low-stock-product',
            'sku' => 'SKU-'.Str::upper(Str::random(8)),
            'store_id' => $storeId,
            'price' => 1000,
            'stock_quantity' => 1, // Only 1 available
            'track_inventory' => true,
            'is_active' => true,
        ]);

        // 2. Simulate Cart with Qty 2
        session(['cart' => [
            $product->id => 2,
        ]]);

        // 3. Make Request
        $response = $this->postJson('/api/orders/create', [
            'recipient_name' => 'John Doe',
            'phone' => '0912345678',
            'village' => 'Test Village',
            'location' => ['lat' => 33.5, 'lng' => 36.3],
            'delivery_method' => 'normal',
            'payment_method' => 'cash',
            'delivery_cost' => 500,
            'service_fee' => 0,
            'address_note' => 'Near the park',
        ]);

        // 4. Assertions
        $response->assertStatus(400); // Expect validation error

        // Verify Stock Unchanged
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 1,
        ]);

        // Verify No Order Created
        $this->assertDatabaseCount('orders', 0);

        // Verify No Financial Transaction
        $this->assertDatabaseCount('financial_transactions', 0);
    }
}

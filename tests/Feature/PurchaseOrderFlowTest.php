<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PurchaseOrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_order_receiving_updates_stock_and_logs_movement(): void
    {
        $employee = Employee::create(array_filter([
            'first_name' => 'Vendor',
            'last_name' => 'User',
            'email' => 'vendor@example.com',
            'password' => 'password123',
            'is_trader' => true,
            'status' => 'active',
            \Illuminate\Support\Facades\Schema::hasColumn('employees', 'department') ? 'department' : null => 'IT',
            \Illuminate\Support\Facades\Schema::hasColumn('employees', 'position') ? 'position' : null => 'Vendor',
            \Illuminate\Support\Facades\Schema::hasColumn('employees', 'hire_date') ? 'hire_date' : null => now()->toDateString(),
            \Illuminate\Support\Facades\Schema::hasColumn('employees', 'employment_type') ? 'employment_type' : null => 'full_time',
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        DB::table('users')->insert(array_filter([
            'id' => $employee->id,
            'name' => 'Store Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('password'),
            \Illuminate\Support\Facades\Schema::hasColumn('users', 'username') ? 'username' : null => 'owner-'.\Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(6)),
            \Illuminate\Support\Facades\Schema::hasColumn('users', 'verified') ? 'verified' : null => true,
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        $store = Store::create(array_filter([
            \Illuminate\Support\Facades\Schema::hasColumn('stores', 'owner_id') ? 'owner_id' : 'user_id' => $employee->id,
            \Illuminate\Support\Facades\Schema::hasColumn('stores', 'organization_id') ? 'organization_id' : null => \Illuminate\Support\Facades\Schema::hasColumn('stores', 'organization_id')
                ? DB::table('organizations')->insertGetId([
                    'name' => 'Org',
                    'slug' => 'org-'.\Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(6)),
                    'status' => 'active',
                ])
                : null,
            'name' => 'Test Store',
            'slug' => 'test-store',
            'description' => 'Demo',
            'status' => 'pending',
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        $categoryId = \Illuminate\Support\Facades\Schema::hasColumn('products', 'category_id')
            ? DB::table('categories')->insertGetId([
                'name' => 'General',
                'slug' => 'cat-'.\Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(6)),
            ])
            : null;

        $product = Product::create(array_filter([
            'store_id' => $store->id,
            'name' => 'Demo Product',
            'slug' => 'demo-product',
            'description' => 'Demo',
            \Illuminate\Support\Facades\Schema::hasColumn('products', 'category_id') ? 'category_id' : null => $categoryId,
            'sku' => 'SKU-001',
            'price' => 10,
            'cost_price' => 5,
            'stock_quantity' => 0,
            'low_stock_threshold' => 10,
            'is_active' => true,
            'status' => 'active',
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        $this->actingAs($employee, 'employee');

        $createResp = $this->postJson(route('dashboard.vendor.purchase-orders.create'), [
            'supplier_name' => 'Supplier A',
            'expected_delivery_date' => now()->addDays(3)->toDateString(),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                    'unit_cost' => 2.5,
                ],
            ],
        ]);
        $createResp->assertStatus(201);
        $poData = $createResp->json('purchase_order');
        $poId = $poData['id'];
        $poItemId = $poData['items'][0]['id'];

        $receiveResp = $this->postJson(route('dashboard.vendor.purchase-orders.receive', ['purchaseOrder' => $poId]), [
            'items' => [
                [
                    'item_id' => $poItemId,
                    'received_quantity' => 3,
                ],
            ],
        ]);
        $receiveResp->assertStatus(200);

        $product->refresh();
        $this->assertEquals(3, $product->stock_quantity);
        $this->assertDatabaseHas('inventory_movements', [
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 3,
            'reason' => 'purchase_order_receipt',
        ]);

        $receiveResp2 = $this->postJson(route('dashboard.vendor.purchase-orders.receive', ['purchaseOrder' => $poId]), [
            'items' => [
                [
                    'item_id' => $poItemId,
                    'received_quantity' => 2,
                ],
            ],
        ]);
        $receiveResp2->assertStatus(200);

        $po = PurchaseOrder::find($poId);
        $poItem = PurchaseOrderItem::find($poItemId);
        $product->refresh();

        $this->assertEquals(5, $product->stock_quantity);
        $this->assertEquals(5, $poItem->received_quantity);
        $this->assertTrue(in_array($po->status, ['partially_received', 'received']));
    }
}

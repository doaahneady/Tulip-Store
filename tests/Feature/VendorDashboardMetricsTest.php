<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class VendorDashboardMetricsTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_dashboard_counts_orders_by_items_and_calculates_earnings_excluding_delivery(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'trader');

        $storeId = DB::table('stores')->insertGetId(array_filter([
            Schema::hasColumn('stores', 'owner_id') ? 'owner_id' : 'user_id' => $user->id,
            'name' => 'Test Store',
            'slug' => 'test-store-'.Str::lower(Str::random(6)),
            Schema::hasColumn('stores', 'status') ? 'status' : null => Schema::hasColumn('stores', 'status') ? 'approved' : null,
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        $product = Product::create([
            'name' => 'ساعة',
            'slug' => 'product-'.Str::lower(Str::random(6)),
            'sku' => 'SKU-'.Str::upper(Str::random(8)),
            'store_id' => $storeId,
            'price' => 100,
            'stock_quantity' => 10,
            'track_inventory' => false,
            'is_active' => true,
        ]);

        $order1 = Order::create(array_filter([
            'order_number' => 'ORD-'.Str::upper(Str::random(10)),
            Schema::hasColumn('orders', 'customer_id') ? 'customer_id' : null => Schema::hasColumn('orders', 'customer_id') ? $user->id : null,
            Schema::hasColumn('orders', 'user_id') ? 'user_id' : null => Schema::hasColumn('orders', 'user_id') ? $user->id : null,
            'store_id' => null,
            'status' => 'done',
            'payment_status' => 'paid',
            'subtotal' => 100,
            Schema::hasColumn('orders', 'shipping_cost') ? 'shipping_cost' : null => Schema::hasColumn('orders', 'shipping_cost') ? 10 : null,
            Schema::hasColumn('orders', 'delivery_cost') ? 'delivery_cost' : null => Schema::hasColumn('orders', 'delivery_cost') ? 0 : null,
            Schema::hasColumn('orders', 'total_amount') ? 'total_amount' : null => Schema::hasColumn('orders', 'total_amount') ? 110 : null,
            Schema::hasColumn('orders', 'total') ? 'total' : null => Schema::hasColumn('orders', 'total') ? 110 : null,
        ], fn ($v, $k) => $k !== null, ARRAY_FILTER_USE_BOTH));

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'quantity' => 1,
            'unit_price' => 100,
            'total_price' => 100,
        ]);

        $order2 = Order::create(array_filter([
            'order_number' => 'ORD-'.Str::upper(Str::random(10)),
            Schema::hasColumn('orders', 'customer_id') ? 'customer_id' : null => Schema::hasColumn('orders', 'customer_id') ? $user->id : null,
            Schema::hasColumn('orders', 'user_id') ? 'user_id' : null => Schema::hasColumn('orders', 'user_id') ? $user->id : null,
            'store_id' => null,
            'status' => 'pending',
            'payment_status' => 'pending',
            'subtotal' => 200,
            Schema::hasColumn('orders', 'shipping_cost') ? 'shipping_cost' : null => Schema::hasColumn('orders', 'shipping_cost') ? 20 : null,
            Schema::hasColumn('orders', 'delivery_cost') ? 'delivery_cost' : null => Schema::hasColumn('orders', 'delivery_cost') ? 0 : null,
            Schema::hasColumn('orders', 'total_amount') ? 'total_amount' : null => Schema::hasColumn('orders', 'total_amount') ? 220 : null,
            Schema::hasColumn('orders', 'total') ? 'total' : null => Schema::hasColumn('orders', 'total') ? 220 : null,
        ], fn ($v, $k) => $k !== null, ARRAY_FILTER_USE_BOTH));

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'quantity' => 2,
            'unit_price' => 100,
            'total_price' => 200,
        ]);

        $order3 = Order::create(array_filter([
            'order_number' => 'ORD-'.Str::upper(Str::random(10)),
            Schema::hasColumn('orders', 'customer_id') ? 'customer_id' : null => Schema::hasColumn('orders', 'customer_id') ? $user->id : null,
            Schema::hasColumn('orders', 'user_id') ? 'user_id' : null => Schema::hasColumn('orders', 'user_id') ? $user->id : null,
            'store_id' => null,
            'status' => 'delivered',
            'payment_status' => 'paid',
            'subtotal' => 200,
            Schema::hasColumn('orders', 'shipping_cost') ? 'shipping_cost' : null => Schema::hasColumn('orders', 'shipping_cost') ? 20 : null,
            Schema::hasColumn('orders', 'delivery_cost') ? 'delivery_cost' : null => Schema::hasColumn('orders', 'delivery_cost') ? 0 : null,
            Schema::hasColumn('orders', 'total_amount') ? 'total_amount' : null => Schema::hasColumn('orders', 'total_amount') ? 220 : null,
            Schema::hasColumn('orders', 'total') ? 'total' : null => Schema::hasColumn('orders', 'total') ? 220 : null,
        ], fn ($v, $k) => $k !== null, ARRAY_FILTER_USE_BOTH));

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'quantity' => 2,
            'unit_price' => 100,
            'total_price' => 200,
        ]);

        $response = $this->withoutMiddleware()->get('/dashboard/vendor');
        $response->assertStatus(200);

        $metrics = $response->viewData('metrics');
        $this->assertSame(3, (int) ($metrics['total_orders'] ?? -1));
        $this->assertSame(3, (int) ($metrics['monthly_orders'] ?? -1));
        $this->assertSame(1, (int) ($metrics['pending_orders'] ?? -1));
        $this->assertSame(2, (int) ($metrics['completed_orders'] ?? -1));
        $this->assertSame(300.0, (float) ($metrics['earnings_ex_delivery_total'] ?? -1));
        $this->assertSame(300.0, (float) ($metrics['earnings_ex_delivery_month'] ?? -1));

        $order2->update(['status' => 'done', 'payment_status' => 'paid']);

        $response2 = $this->withoutMiddleware()->get('/dashboard/vendor');
        $response2->assertStatus(200);
        $metrics2 = $response2->viewData('metrics');
        $this->assertSame(3, (int) ($metrics2['completed_orders'] ?? -1));
        $this->assertSame(500.0, (float) ($metrics2['earnings_ex_delivery_total'] ?? -1));
    }
}


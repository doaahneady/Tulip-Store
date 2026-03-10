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
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserOrdersApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_orders_api_returns_delivery_cost_and_arabic_product_name(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $storeId = null;
        if (Schema::hasTable('stores') && Schema::hasColumn('products', 'store_id')) {
            $storeId = DB::table('stores')->insertGetId(array_filter([
                Schema::hasColumn('stores', 'owner_id') ? 'owner_id' : (Schema::hasColumn('stores', 'user_id') ? 'user_id' : null) => $user->id,
                'name' => 'Test Store',
                'slug' => 'test-store-'.Str::lower(Str::random(6)),
                Schema::hasColumn('stores', 'status') ? 'status' : null => Schema::hasColumn('stores', 'status') ? 'approved' : null,
            ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));
        }

        $product = Product::create(array_filter([
            'name' => 'مكسرات',
            'slug' => 'product-'.Str::lower(Str::random(6)),
            'sku' => 'SKU-'.Str::upper(Str::random(8)),
            Schema::hasColumn('products', 'store_id') ? 'store_id' : null => $storeId,
            'price' => 50,
            'stock_quantity' => 10,
            'track_inventory' => false,
            'is_active' => true,
        ], fn ($v, $k) => $k !== null, ARRAY_FILTER_USE_BOTH));

        $order = Order::create(array_filter([
            'order_number' => 'ORD-'.Str::upper(Str::random(10)),
            Schema::hasColumn('orders', 'customer_id') ? 'customer_id' : null => Schema::hasColumn('orders', 'customer_id') ? $user->id : null,
            Schema::hasColumn('orders', 'user_id') ? 'user_id' : null => Schema::hasColumn('orders', 'user_id') ? $user->id : null,
            'status' => 'pending',
            'payment_status' => 'pending',
            'subtotal' => 50,
            Schema::hasColumn('orders', 'shipping_cost') ? 'shipping_cost' : null => Schema::hasColumn('orders', 'shipping_cost') ? 15 : null,
            Schema::hasColumn('orders', 'delivery_cost') ? 'delivery_cost' : null => Schema::hasColumn('orders', 'delivery_cost') ? 0 : null,
            Schema::hasColumn('orders', 'total_amount') ? 'total_amount' : null => Schema::hasColumn('orders', 'total_amount') ? 65 : null,
            Schema::hasColumn('orders', 'total') ? 'total' : null => Schema::hasColumn('orders', 'total') ? 65 : null,
        ], fn ($v, $k) => $k !== null, ARRAY_FILTER_USE_BOTH));

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => 'مكسرات',
            'product_sku' => $product->sku,
            'quantity' => 1,
            'unit_price' => 50,
            'total_price' => 50,
        ]);

        $res = $this->getJson('/api/user/orders');
        $res->assertStatus(200);
        $res->assertJsonPath('orders.0.delivery_cost', 15);
        $res->assertJsonPath('orders.0.items.0.product_name', 'مكسرات');
    }
}

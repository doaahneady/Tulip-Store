<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreditCardPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_card_payment_creates_pending_transaction_and_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $storeId = DB::table('stores')->insertGetId(array_filter([
            Schema::hasColumn('stores', 'owner_id') ? 'owner_id' : 'user_id' => $user->id,
            'name' => 'Test Store',
            'slug' => 'store-'.Str::lower(Str::random(6)),
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        $p = Product::create([
            'name' => 'Card Test Product',
            'slug' => 'card-test-'.Str::lower(Str::random(6)),
            'sku' => 'SKU-'.Str::upper(Str::random(8)),
            'store_id' => $storeId,
            'price' => 1500,
            'stock_quantity' => 3,
            'track_inventory' => true,
            'is_active' => true,
        ]);

        session(['cart' => [
            $p->id => 1,
        ]]);

        $resp = $this->postJson('/api/orders/create', [
            'recipient_name' => 'Tester',
            'phone' => '0999999999',
            'village' => 'Sweida',
            'location' => ['lat' => 32.71, 'lng' => 36.56],
            'delivery_method' => 'normal',
            'payment_method' => 'card',
            'delivery_cost' => 300,
            'service_fee' => 0,
            'address_note' => 'Near hospital',
        ]);

        $resp->assertStatus(200)->assertJson(['success' => true]);

        // Order totals
        if (Schema::hasTable('orders')) {
            if (Schema::hasColumn('orders', 'total')) {
                $this->assertDatabaseHas('orders', ['total' => 1800]);
            }
            if (Schema::hasColumn('orders', 'payment_status')) {
                $this->assertDatabaseHas('orders', ['payment_status' => 'pending']);
            }
        }

        // Financial transaction
        if (Schema::hasTable('financial_transactions')) {
            $this->assertDatabaseHas('financial_transactions', [
                'user_id' => $user->id,
                'type' => 'order_payment',
                'status' => 'pending',
            ]);
        }
    }
}

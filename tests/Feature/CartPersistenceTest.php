<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class CartPersistenceTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(): Product
    {
        $user = User::factory()->create();
        $storeId = DB::table('stores')->insertGetId(array_filter([
            Schema::hasColumn('stores', 'owner_id') ? 'owner_id' : 'user_id' => $user->id,
            'name' => 'Store',
            'slug' => 's-'.Str::lower(Str::random(6)),
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        return Product::create([
            'name' => 'Persist Product',
            'slug' => 'persist-'.Str::lower(Str::random(6)),
            'sku' => 'SKU-'.Str::upper(Str::random(8)),
            'store_id' => $storeId,
            'price' => 100,
            'stock_quantity' => 10,
            'track_inventory' => true,
            'is_active' => true,
        ]);
    }

    public function test_remove_one_item_keeps_other_items()
    {
        $p1 = $this->createProduct();
        $p2 = $this->createProduct();

        // Guest session cart
        session(['cart' => [
            $p1->id => 2,
            $p2->id => 1,
        ]]);

        // Remove p1
        $res = $this->postJson('/api/cart/remove', ['item_id' => $p1->id]);
        $res->assertStatus(200)->assertJson(['success' => true]);

        $this->assertEquals(1, session('cart')[$p2->id] ?? 0);
        $this->assertArrayNotHasKey((string)$p1->id, session('cart', []));
    }

    public function test_cart_merges_into_database_after_login()
    {
        $p = $this->createProduct();
        session(['cart' => [
            $p->id => 3,
        ]]);

        $user = User::factory()->create();
        $this->actingAs($user);

        // Trigger merge via index endpoint
        $res = $this->getJson('/api/cart');
        $res->assertStatus(200);

        // Session cart cleared, DB cart should contain the item
        $this->assertEmpty(session('cart', []));
        if (Schema::hasTable('carts') && Schema::hasTable('cart_items')) {
            $cartId = DB::table('carts')->where('user_id', $user->id)->value('id');
            $this->assertNotNull($cartId);
            $qty = DB::table('cart_items')->where('cart_id', $cartId)->where('product_id', $p->id)->value('quantity');
            $this->assertEquals(3, (int) $qty);
        }
    }
}

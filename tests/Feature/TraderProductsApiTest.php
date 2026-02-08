<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Trader;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TraderProductsApiTest extends TestCase
{
    use RefreshDatabase;

    protected function makeApprovedTrader(): array
    {
        $user = User::factory()->create([
            'email' => 'trader+'.Str::lower(Str::random(6)).'@example.com',
            'password' => bcrypt('password123'),
            'is_trader' => true,
        ]);

        $trader = Trader::create([
            'user_id' => $user->id,
            'name' => 'Demo Trader',
            'contact_email' => $user->email,
            'status' => Trader::STATUS_APPROVED,
            'commission_rate' => 12.5,
        ]);

        $token = $user->createToken('test')->plainTextToken;

        return [$user, $trader, $token];
    }

    public function test_edit_product_sets_pending_when_previously_approved(): void
    {
        [$user, $trader, $token] = $this->makeApprovedTrader();

        $product = Product::create([
            'name' => 'Prod A',
            'slug' => 'prod-a',
            'trader_id' => $trader->id,
            'price' => 100,
            'status' => 'approved',
            'stock_quantity' => 5,
            'track_inventory' => true,
            'is_active' => true,
        ]);

        $res = $this->withHeader('Authorization', 'Bearer '.$token)
            ->patchJson('/api/trader/products/'.$product->id, [
                'name' => 'Prod A+',
                'price' => 120,
            ]);

        $res->assertOk();
        $product->refresh();
        $this->assertEquals('pending', $product->status);
        $this->assertEquals('Prod A+', $product->name);
    }

    public function test_bulk_stock_add_and_reduce(): void
    {
        [$user, $trader, $token] = $this->makeApprovedTrader();

        $p1 = Product::create([
            'name' => 'P1',
            'slug' => 'p1-'.Str::lower(Str::random(6)),
            'trader_id' => $trader->id,
            'price' => 10,
            'stock_quantity' => 2,
            'track_inventory' => true,
            'is_active' => true,
        ]);

        $add = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/trader/products/bulk-stock', [
                'product_ids' => [$p1->id],
                'mode' => 'add',
                'quantity' => 3,
                'reason' => 'restock',
            ]);
        $add->assertOk();
        $p1->refresh();
        $this->assertEquals(5, $p1->stock_quantity);

        $reduce = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/trader/products/bulk-stock', [
                'product_ids' => [$p1->id],
                'mode' => 'reduce',
                'quantity' => 2,
                'reason' => 'correction',
            ]);
        $reduce->assertOk();
        $this->assertEquals(3, $p1->fresh()->stock_quantity);
    }

    public function test_product_analytics_returns_metrics(): void
    {
        [$user, $trader, $token] = $this->makeApprovedTrader();

        $product = Product::create([
            'name' => 'Analytics Prod',
            'slug' => 'analytics-'.Str::lower(Str::random(6)),
            'trader_id' => $trader->id,
            'price' => 50,
            'stock_quantity' => 10,
            'track_inventory' => true,
            'is_active' => true,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-T-'.Str::upper(Str::random(6)),
            'customer_id' => $user->id,
            'status' => 'completed',
            'total' => 100,
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 2,
            'unit_price' => 50,
            'total_price' => 100,
        ]);

        $res = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/trader/products/'.$product->id.'/analytics');

        $res->assertOk()
            ->assertJsonPath('metrics.units_sold', 2)
            ->assertJsonPath('metrics.revenue', 100.0)
            ->assertJsonStructure(['metrics' => ['revenue_trend']]);
    }
}

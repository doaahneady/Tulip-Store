<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Trader;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TraderAnalyticsApiTest extends TestCase
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
            'name' => 'Analytics Trader',
            'contact_email' => $user->email,
            'status' => Trader::STATUS_APPROVED,
            'commission_rate' => 10,
        ]);

        $token = $user->createToken('test')->plainTextToken;

        return [$user, $trader, $token];
    }

    protected function seedBasicSales($trader, $count = 3): array
    {
        $cat = Category::create(['name' => 'Cat A', 'slug' => 'cat-a']);

        $products = [];
        for ($i = 0; $i < $count; $i++) {
            $products[] = Product::create([
                'name' => 'P'.$i,
                'slug' => 'p-'.$i.'-'.Str::lower(Str::random(4)),
                'trader_id' => $trader->id,
                'category_id' => $cat->id,
                'price' => 50 + $i,
                'stock_quantity' => 10 + $i,
                'track_inventory' => true,
                'is_active' => true,
                'status' => 'approved',
            ]);
        }

        $order = Order::create([
            'order_number' => 'ORD-AN-'.Str::upper(Str::random(6)),
            'customer_id' => $trader->user_id,
            'status' => 'delivered',
            'payment_status' => 'paid',
            'total' => 0,
        ]);

        $total = 0;
        foreach ($products as $i => $p) {
            $qty = $i + 1;
            $line = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $p->id,
                'product_name' => $p->name,
                'quantity' => $qty,
                'unit_price' => $p->price,
                'total_price' => $p->price * $qty,
            ]);
            $total += $line->total_price;
        }
        $order->update(['total' => $total]);

        return [$products, $order];
    }

    public function test_sales_analytics_overview_and_trend(): void
    {
        [$user, $trader, $token] = $this->makeApprovedTrader();
        $this->seedBasicSales($trader, 3);

        $res = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/trader/analytics/sales?range=month');

        $res->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'overview' => ['total_sales', 'units_sold', 'orders'],
                'trend',
                'by_category',
                'by_day_of_week',
                'by_hour',
            ]);
    }

    public function test_product_performance_metrics(): void
    {
        [$user, $trader, $token] = $this->makeApprovedTrader();
        $this->seedBasicSales($trader, 3);

        $res = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/trader/analytics/products?range=month');

        $res->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'best_sellers',
                'best_revenue',
                'worst_performers',
                'high_return_rate',
                'stock_turnover',
            ]);
    }

    public function test_customer_insights_metrics(): void
    {
        [$user, $trader, $token] = $this->makeApprovedTrader();
        $this->seedBasicSales($trader, 2);

        $res = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/trader/analytics/customers?range=year');

        $res->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'unique_customers',
                'repeat_customer_rate',
                'average_order_value',
                'locations',
            ]);
    }

    public function test_inventory_analytics_metrics(): void
    {
        [$user, $trader, $token] = $this->makeApprovedTrader();
        $this->seedBasicSales($trader, 3);

        $res = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/trader/analytics/inventory');

        $res->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'current_inventory_value',
                'overstocked',
                'understocked',
                'dead_stock',
                'inventory_turnover_rate',
            ]);
    }

    public function test_competitive_analysis_metrics(): void
    {
        [$user, $trader, $token] = $this->makeApprovedTrader();
        $this->seedBasicSales($trader, 2);

        $res = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/trader/analytics/competitive');

        $res->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'sales_vs_platform' => ['trader_revenue', 'platform_revenue'],
                'commission_paid' => ['trader_commission', 'platform_avg_rate'],
                'approval_rate' => ['trader', 'platform'],
            ]);
    }
}

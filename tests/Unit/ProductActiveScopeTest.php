<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductActiveScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_scope_includes_non_trader_products_even_when_status_is_pending(): void
    {
        $cat = Category::create([
            'name' => 'Fruits',
            'slug' => 'fruits',
            'market' => 'mart',
            'is_active' => true,
        ]);

        $p = Product::create([
            'name' => 'Apple',
            'slug' => 'apple',
            'category_id' => $cat->id,
            'is_active' => true,
            'market' => 'mart',
            'status' => 'pending',
            'is_trader_product' => false,
            'price' => 1,
        ]);

        $ids = Product::query()->active()->pluck('id')->all();
        $this->assertContains($p->id, $ids);
    }

    public function test_active_scope_excludes_pending_trader_products(): void
    {
        $cat = Category::create([
            'name' => 'Fruits',
            'slug' => 'fruits',
            'market' => 'mart',
            'is_active' => true,
        ]);

        $pendingTrader = Product::create([
            'name' => 'Trader Apple',
            'slug' => 'trader-apple',
            'category_id' => $cat->id,
            'is_active' => true,
            'market' => 'mart',
            'status' => 'pending',
            'is_trader_product' => true,
            'price' => 1,
        ]);

        $approvedTrader = Product::create([
            'name' => 'Trader Orange',
            'slug' => 'trader-orange',
            'category_id' => $cat->id,
            'is_active' => true,
            'market' => 'mart',
            'status' => 'approved',
            'is_trader_product' => true,
            'price' => 1,
        ]);

        $ids = Product::query()->active()->pluck('id')->all();
        $this->assertNotContains($pendingTrader->id, $ids);
        $this->assertContains($approvedTrader->id, $ids);
    }
}


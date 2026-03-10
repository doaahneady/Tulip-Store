<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MartProductsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_mart_products_endpoint_does_not_return_store_products(): void
    {
        $storeCategory = Category::create([
            'name' => 'Store Cat',
            'slug' => 'store-cat',
            'market' => 'store',
            'is_active' => true,
            'display_order' => 1,
        ]);
        $martCategory = Category::create([
            'name' => 'Mart Cat',
            'slug' => 'mart-cat',
            'market' => 'mart',
            'is_active' => true,
            'display_order' => 1,
        ]);

        $storeProduct = Product::create([
            'name' => 'Store Product',
            'slug' => 'store-product',
            'category_id' => $storeCategory->id,
            'is_active' => true,
            'market' => 'store',
            'status' => 'pending',
            'is_trader_product' => false,
            'price' => 10,
        ]);

        $martProduct = Product::create([
            'name' => 'Mart Product',
            'slug' => 'mart-product',
            'category_id' => $martCategory->id,
            'is_active' => true,
            'market' => 'mart',
            'status' => 'pending',
            'is_trader_product' => false,
            'price' => 10,
        ]);

        $res = $this->getJson('/api/products?market=mart&per_page=1000');
        $res->assertOk();

        $ids = collect($res->json('data', []))->pluck('id')->all();
        $this->assertContains($martProduct->id, $ids);
        $this->assertNotContains($storeProduct->id, $ids);
    }

    public function test_search_does_not_leak_store_products_into_mart_market(): void
    {
        $storeCategory = Category::create([
            'name' => 'Store Cat',
            'slug' => 'store-cat',
            'market' => 'store',
            'is_active' => true,
        ]);
        $martCategory = Category::create([
            'name' => 'Mart Cat',
            'slug' => 'mart-cat',
            'market' => 'mart',
            'is_active' => true,
        ]);

        $storeProduct = Product::create([
            'name' => 'Unrelated',
            'slug' => 'unrelated',
            'description' => 'banana banana banana',
            'category_id' => $storeCategory->id,
            'is_active' => true,
            'market' => 'store',
            'status' => 'pending',
            'is_trader_product' => false,
            'price' => 10,
        ]);

        Product::create([
            'name' => 'Mart One',
            'slug' => 'mart-one',
            'category_id' => $martCategory->id,
            'is_active' => true,
            'market' => 'mart',
            'status' => 'pending',
            'is_trader_product' => false,
            'price' => 10,
        ]);

        $res = $this->getJson('/api/products?market=mart&search=banana&per_page=1000');
        $res->assertOk();

        $ids = collect($res->json('data', []))->pluck('id')->all();
        $this->assertNotContains($storeProduct->id, $ids);
    }

    public function test_mart_products_are_returned_even_when_category_slug_is_not_in_legacy_list(): void
    {
        $martCategory = Category::create([
            'name' => 'Misc Mart',
            'slug' => 'misc-mart',
            'market' => 'mart',
            'is_active' => true,
        ]);

        $p = Product::create([
            'name' => 'Mart Misc',
            'slug' => 'mart-misc',
            'category_id' => $martCategory->id,
            'is_active' => true,
            'market' => 'mart',
            'status' => 'pending',
            'is_trader_product' => false,
            'price' => 10,
        ]);

        $res = $this->getJson('/api/products?market=mart&per_page=1000');
        $res->assertOk();

        $ids = collect($res->json('data', []))->pluck('id')->all();
        $this->assertContains($p->id, $ids);
    }
}


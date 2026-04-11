<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MartNavigationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_mart_navigation_returns_categories_with_subcategories_and_counts(): void
    {
        $cat = Category::create(array_filter([
            'name' => 'Meat',
            'slug' => 'meat',
            Schema::hasColumn('categories', 'market') ? 'market' : null => 'mart',
            Schema::hasColumn('categories', 'is_active') ? 'is_active' : null => true,
            Schema::hasColumn('categories', 'display_order') ? 'display_order' : null => 1,
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        if (! Schema::hasTable('subcategories')) {
            $this->markTestSkipped('subcategories table does not exist');
        }

        $sub = Subcategory::create([
            'category_id' => $cat->id,
            'name' => 'Beef',
            'slug' => 'beef',
            'display_order' => 0,
            'is_active' => true,
        ]);

        $productData = array_filter([
            'name' => 'Ribeye',
            'slug' => 'ribeye',
            'category_id' => $cat->id,
            Schema::hasColumn('products', 'subcategory_id') ? 'subcategory_id' : null => $sub->id,
            Schema::hasColumn('products', 'market') ? 'market' : null => 'mart',
            Schema::hasColumn('products', 'is_active') ? 'is_active' : null => true,
            Schema::hasColumn('products', 'status') ? 'status' : null => 'pending',
            Schema::hasColumn('products', 'is_trader_product') ? 'is_trader_product' : null => false,
            'price' => 10,
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH);

        Product::create($productData);

        $res = $this->getJson('/api/mart/navigation');
        $res->assertOk();

        $data = $res->json('data', []);
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        $first = $data[0];
        $this->assertArrayHasKey('subcategories', $first);
        $subs = $first['subcategories'] ?? [];
        $this->assertIsArray($subs);
        $this->assertNotEmpty($subs);
        $this->assertEquals('beef', $subs[0]['slug']);
        $this->assertEquals(1, (int) ($subs[0]['products_count'] ?? 0));
    }

    public function test_products_by_subcategory_requires_matching_category_when_provided(): void
    {
        $cat = Category::create(array_filter([
            'name' => 'Dairy',
            'slug' => 'dairy',
            Schema::hasColumn('categories', 'market') ? 'market' : null => 'mart',
            Schema::hasColumn('categories', 'is_active') ? 'is_active' : null => true,
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        if (! Schema::hasTable('subcategories') || ! Schema::hasColumn('products', 'subcategory_id')) {
            $this->markTestSkipped('subcategories or products.subcategory_id is missing');
        }

        $sub = Subcategory::create([
            'category_id' => $cat->id,
            'name' => 'Milk',
            'slug' => 'milk',
            'display_order' => 0,
            'is_active' => true,
        ]);

        $p = Product::create(array_filter([
            'name' => 'Whole Milk',
            'slug' => 'whole-milk',
            'category_id' => $cat->id,
            'subcategory_id' => $sub->id,
            Schema::hasColumn('products', 'market') ? 'market' : null => 'mart',
            Schema::hasColumn('products', 'is_active') ? 'is_active' : null => true,
            Schema::hasColumn('products', 'status') ? 'status' : null => 'pending',
            Schema::hasColumn('products', 'is_trader_product') ? 'is_trader_product' : null => false,
            'price' => 5,
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        $ok = $this->getJson('/api/mart/subcategories/milk/products?category=dairy&per_page=1000');
        $ok->assertOk();
        $ids = collect($ok->json('products.data', []))->pluck('id')->all();
        $this->assertContains($p->id, $ids);

        $bad = $this->getJson('/api/mart/subcategories/milk/products?category=wrong-cat');
        $bad->assertStatus(404);
    }
}


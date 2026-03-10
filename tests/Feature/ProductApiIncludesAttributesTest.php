<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductApiIncludesAttributesTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_show_api_returns_attributes(): void
    {
        $cat = Category::create(array_filter([
            'name' => 'Cat',
            'slug' => 'cat',
            Schema::hasColumn('categories', 'market') ? 'market' : null => 'store',
            Schema::hasColumn('categories', 'is_active') ? 'is_active' : null => true,
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        $product = Product::create(array_filter([
            'name' => 'Product',
            'slug' => 'product',
            'category_id' => $cat->id,
            'price' => 100,
            Schema::hasColumn('products', 'market') ? 'market' : null => 'store',
            Schema::hasColumn('products', 'is_active') ? 'is_active' : null => true,
            Schema::hasColumn('products', 'status') ? 'status' : null => 'approved',
            Schema::hasColumn('products', 'is_trader_product') ? 'is_trader_product' : null => false,
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        ProductAttribute::create([
            'product_id' => $product->id,
            'name' => 'material',
            'attribute_key' => 'material',
            'type' => 'text',
            'value' => 'cotton',
            'value_text' => 'cotton',
            'options' => [],
            'is_custom' => true,
        ]);

        $res = $this->getJson('/api/products/'.$product->id.'?market=store');
        $res->assertOk()
            ->assertJsonPath('id', $product->id)
            ->assertJsonPath('attributes.0.attribute_key', 'material');
    }

    public function test_product_index_can_filter_by_attribute_key_value(): void
    {
        $cat = Category::create(array_filter([
            'name' => 'Cat',
            'slug' => 'cat',
            Schema::hasColumn('categories', 'market') ? 'market' : null => 'store',
            Schema::hasColumn('categories', 'is_active') ? 'is_active' : null => true,
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        $p1 = Product::create(array_filter([
            'name' => 'P1',
            'slug' => 'p1',
            'category_id' => $cat->id,
            'price' => 10,
            Schema::hasColumn('products', 'market') ? 'market' : null => 'store',
            Schema::hasColumn('products', 'is_active') ? 'is_active' : null => true,
            Schema::hasColumn('products', 'status') ? 'status' : null => 'approved',
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));
        $p2 = Product::create(array_filter([
            'name' => 'P2',
            'slug' => 'p2',
            'category_id' => $cat->id,
            'price' => 20,
            Schema::hasColumn('products', 'market') ? 'market' : null => 'store',
            Schema::hasColumn('products', 'is_active') ? 'is_active' : null => true,
            Schema::hasColumn('products', 'status') ? 'status' : null => 'approved',
        ], fn ($v, $k) => $k !== null && $v !== null, ARRAY_FILTER_USE_BOTH));

        ProductAttribute::create([
            'product_id' => $p1->id,
            'name' => 'material',
            'attribute_key' => 'material',
            'type' => 'text',
            'value' => 'cotton',
            'value_text' => 'cotton',
            'options' => [],
            'is_custom' => true,
        ]);
        ProductAttribute::create([
            'product_id' => $p2->id,
            'name' => 'material',
            'attribute_key' => 'material',
            'type' => 'text',
            'value' => 'wool',
            'value_text' => 'wool',
            'options' => [],
            'is_custom' => true,
        ]);

        $res = $this->getJson('/api/products?market=store&attr_key=material&attr_value=cotton');
        $res->assertOk();
        $data = $res->json('data');
        $ids = array_map(fn ($x) => $x['id'], is_array($data) ? $data : []);
        $this->assertContains($p1->id, $ids);
        $this->assertNotContains($p2->id, $ids);
    }
}


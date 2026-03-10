<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductDetailsAttributesTest extends TestCase
{
    use RefreshDatabase;

    private function createStoreCategory(): Category
    {
        $data = [
            'name' => 'Cat',
            'slug' => 'cat',
        ];
        if (Schema::hasColumn('categories', 'market')) {
            $data['market'] = 'store';
        }
        if (Schema::hasColumn('categories', 'is_active')) {
            $data['is_active'] = true;
        }
        if (Schema::hasColumn('categories', 'display_order')) {
            $data['display_order'] = 1;
        }

        return Category::create($data);
    }

    private function createStoreProduct(Category $category): Product
    {
        $data = [
            'name' => 'Product',
            'slug' => 'product',
            'category_id' => $category->id,
            'price' => 100,
        ];
        if (Schema::hasColumn('products', 'market')) {
            $data['market'] = 'store';
        }
        if (Schema::hasColumn('products', 'is_active')) {
            $data['is_active'] = true;
        }
        if (Schema::hasColumn('products', 'status')) {
            $data['status'] = 'approved';
        }
        if (Schema::hasColumn('products', 'is_trader_product')) {
            $data['is_trader_product'] = false;
        }

        return Product::create($data);
    }

    public function test_product_details_hides_attributes_section_when_zero_attributes(): void
    {
        $cat = $this->createStoreCategory();
        $product = $this->createStoreProduct($cat);

        $res = $this->get('/products/'.$product->id);
        $res->assertOk();
        $res->assertDontSee('id="productAttributes"', false);
    }

    public function test_product_details_renders_attributes_section_with_one_attribute(): void
    {
        $cat = $this->createStoreCategory();
        $product = $this->createStoreProduct($cat);

        ProductAttribute::create([
            'product_id' => $product->id,
            'name' => 'ملاحظة',
            'type' => 'text',
            'value' => 'قيمة',
            'options' => [],
            'is_custom' => true,
        ]);

        $res = $this->get('/products/'.$product->id);
        $res->assertOk();
        $res->assertSee('id="productAttributes"', false);
        $this->assertSame(1, substr_count($res->getContent(), 'class="attr-field"'));
    }

    public function test_product_details_renders_three_attributes_with_mixed_types(): void
    {
        $cat = $this->createStoreCategory();
        $product = $this->createStoreProduct($cat);

        ProductAttribute::insert([
            [
                'product_id' => $product->id,
                'name' => 'نوع',
                'type' => 'select',
                'value' => 'A',
                'options' => json_encode(['A', 'B']),
                'is_custom' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $product->id,
                'name' => 'تفعيل',
                'type' => 'checkbox',
                'value' => '1',
                'options' => json_encode([]),
                'is_custom' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $product->id,
                'name' => 'لون',
                'type' => 'color',
                'value' => '#ff0000',
                'options' => json_encode(['#ff0000', '#00ff00']),
                'is_custom' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $res = $this->get('/products/'.$product->id);
        $res->assertOk();
        $res->assertSee('id="productAttributes"', false);
        $this->assertSame(3, substr_count($res->getContent(), 'class="attr-field"'));
        $res->assertSee('<select', false);
        $res->assertSee('type="checkbox"', false);
        $res->assertSee('role="radiogroup"', false);
    }

    public function test_product_details_limits_attributes_to_five(): void
    {
        $cat = $this->createStoreCategory();
        $product = $this->createStoreProduct($cat);

        for ($i = 1; $i <= 6; $i++) {
            ProductAttribute::create([
                'product_id' => $product->id,
                'name' => 'Attr '.$i,
                'type' => 'text',
                'value' => 'V'.$i,
                'options' => [],
                'is_custom' => true,
            ]);
        }

        $res = $this->get('/products/'.$product->id);
        $res->assertOk();
        $this->assertSame(5, substr_count($res->getContent(), 'class="attr-field"'));
    }

    public function test_product_details_renders_file_attribute_as_link(): void
    {
        $cat = $this->createStoreCategory();
        $product = $this->createStoreProduct($cat);

        ProductAttribute::create([
            'product_id' => $product->id,
            'name' => 'ملف',
            'type' => 'file',
            'value' => 'products/trader/attributes/demo.pdf',
            'value_text' => 'products/trader/attributes/demo.pdf',
            'options' => [],
            'is_custom' => true,
        ]);

        $res = $this->get('/products/'.$product->id);
        $res->assertOk();
        $res->assertSee('تحميل الملف', false);
    }
}

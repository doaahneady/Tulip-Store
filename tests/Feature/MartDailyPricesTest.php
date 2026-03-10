<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class MartDailyPricesTest extends TestCase
{
    use RefreshDatabase;

    private function makeStore(string $name): Store
    {
        $user = User::factory()->create();
        $slug = Str::slug($name).'-'.Str::lower(Str::random(6));

        $data = [
            'name' => $name,
            'slug' => $slug,
        ];

        if (Schema::hasColumn('stores', 'organization_id') && Schema::hasTable('organizations')) {
            $orgId = DB::table('organizations')->insertGetId([
                'name' => 'Org '.$slug,
                'slug' => 'org-'.$slug,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $data['organization_id'] = $orgId;
        }

        if (Schema::hasColumn('stores', 'owner_id')) {
            $data['owner_id'] = $user->id;
        } elseif (Schema::hasColumn('stores', 'user_id')) {
            $data['user_id'] = $user->id;
        }

        if (Schema::hasColumn('stores', 'status')) {
            $data['status'] = 'active';
        }

        return Store::create($data);
    }

    public function test_only_fruits_and_vegetables_are_returned(): void
    {
        $fruits = Category::create([
            'name' => 'فواكه',
            'slug' => 'fruits',
            'market' => 'mart',
            'is_active' => true,
            'display_order' => 1,
        ]);

        $vegetables = Category::create([
            'name' => 'خضار',
            'slug' => 'vegetables',
            'market' => 'mart',
            'is_active' => true,
            'display_order' => 2,
        ]);

        $dairy = Category::create([
            'name' => 'ألبان',
            'slug' => 'dairy',
            'market' => 'mart',
            'is_active' => true,
            'display_order' => 3,
        ]);

        $fruitProduct = Product::create([
            'name' => 'Apple',
            'slug' => 'apple',
            'category_id' => $fruits->id,
            'price' => 10,
            'is_active' => true,
            'market' => 'mart',
        ]);

        $vegProduct = Product::create([
            'name' => 'Cucumber',
            'slug' => 'cucumber',
            'category_id' => $vegetables->id,
            'price' => 7,
            'is_active' => true,
            'market' => 'mart',
        ]);

        $dairyProduct = Product::create([
            'name' => 'Milk',
            'slug' => 'milk',
            'category_id' => $dairy->id,
            'price' => 5,
            'is_active' => true,
            'market' => 'mart',
        ]);

        $res = $this->getJson('/api/mart/daily-prices');
        $res->assertOk();

        $payload = $res->json();
        $this->assertArrayHasKey('categories', $payload);
        $this->assertArrayHasKey('fruits', $payload['categories']);
        $this->assertArrayHasKey('vegetables', $payload['categories']);
        $this->assertArrayNotHasKey('dairy', $payload['categories']);

        $returnedIds = collect($payload['categories'])
            ->flatMap(fn ($items) => collect($items)->pluck('id'))
            ->values()
            ->all();

        $this->assertContains((string) $fruitProduct->id, $returnedIds);
        $this->assertContains((string) $vegProduct->id, $returnedIds);
        $this->assertNotContains((string) $dairyProduct->id, $returnedIds);
    }

    public function test_tulip_mart_vendor_products_are_included_when_in_allowed_categories(): void
    {
        $fruits = Category::create([
            'name' => 'Fruits',
            'slug' => 'fruits',
            'market' => 'mart',
            'is_active' => true,
            'display_order' => 1,
        ]);

        $tulipMart = $this->makeStore('Tulip Mart');

        $tulipMartFruitProduct = Product::create([
            'name' => 'Banana',
            'slug' => 'banana',
            'category_id' => $fruits->id,
            'store_id' => $tulipMart->id,
            'price' => 11,
            'is_active' => true,
            'market' => 'mart',
        ]);

        $res = $this->getJson('/api/mart/daily-prices');
        $res->assertOk();

        $ids = collect($res->json('categories.fruits', []))->pluck('id')->all();
        $this->assertContains((string) $tulipMartFruitProduct->id, $ids);
    }

    public function test_other_vendors_are_not_regressed_and_non_allowed_categories_are_excluded(): void
    {
        $fruits = Category::create([
            'name' => 'Fruits',
            'slug' => 'fruits',
            'market' => 'mart',
            'is_active' => true,
            'display_order' => 1,
        ]);
        $vegetables = Category::create([
            'name' => 'Vegetables',
            'slug' => 'vegetables',
            'market' => 'mart',
            'is_active' => true,
            'display_order' => 2,
        ]);
        $bakery = Category::create([
            'name' => 'Bakery',
            'slug' => 'bakery',
            'market' => 'mart',
            'is_active' => true,
            'display_order' => 3,
        ]);

        $otherVendor = $this->makeStore('Another Vendor');
        $fruitOtherVendor = Product::create([
            'name' => 'Orange',
            'slug' => 'orange',
            'category_id' => $fruits->id,
            'store_id' => $otherVendor->id,
            'price' => 12,
            'is_active' => true,
            'market' => 'mart',
        ]);
        $vegOtherVendor = Product::create([
            'name' => 'Tomato',
            'slug' => 'tomato',
            'category_id' => $vegetables->id,
            'store_id' => $otherVendor->id,
            'price' => 9,
            'is_active' => true,
            'market' => 'mart',
        ]);
        $bakeryOtherVendor = Product::create([
            'name' => 'Bread',
            'slug' => 'bread',
            'category_id' => $bakery->id,
            'store_id' => $otherVendor->id,
            'price' => 4,
            'is_active' => true,
            'market' => 'mart',
        ]);

        $res = $this->getJson('/api/mart/daily-prices');
        $res->assertOk();

        $returnedIds = collect($res->json('categories', []))
            ->flatMap(fn ($items) => collect($items)->pluck('id'))
            ->values()
            ->all();

        $this->assertContains((string) $fruitOtherVendor->id, $returnedIds);
        $this->assertContains((string) $vegOtherVendor->id, $returnedIds);
        $this->assertNotContains((string) $bakeryOtherVendor->id, $returnedIds);
    }
}


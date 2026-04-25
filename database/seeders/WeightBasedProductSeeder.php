<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WeightBasedProductSeeder extends Seeder
{
    /**
     * Seed weight-based products for testing
     */
    public function run(): void
    {
        // Find or create a mart category
        $category = Category::firstOrCreate(
            ['slug' => 'fruits-vegetables'],
            [
                'name' => 'فواكه وخضروات',
                'market' => 'mart',
                'is_active' => true,
                'display_order' => 1,
            ]
        );

        // Find or create a subcategory
        $subcategory = Subcategory::firstOrCreate(
            ['slug' => 'fresh-fruits', 'category_id' => $category->id],
            [
                'name' => 'فواكه طازجة',
                'is_active' => true,
                'display_order' => 1,
            ]
        );

        // Sample weight-based products
        $products = [
            [
                'name' => 'تفاح أحمر',
                'price' => 3500, // 3500 دينار per kg
                'unit' => 'kilogram',
                'origin' => 'محلي',
            ],
            [
                'name' => 'موز',
                'price' => 2500,
                'unit' => 'kilogram',
                'origin' => 'مستورد',
            ],
            [
                'name' => 'برتقال',
                'price' => 2000,
                'unit' => 'كيلو',
                'origin' => 'محلي',
            ],
            [
                'name' => 'طماطم',
                'price' => 1500,
                'unit' => 'كيلوغرام',
                'origin' => 'محلي',
            ],
            [
                'name' => 'خيار',
                'price' => 1000,
                'unit' => 'gram',
                'origin' => 'محلي',
            ],
        ];

        foreach ($products as $productData) {
            $unit = $productData['unit'];
            $origin = $productData['origin'];
            unset($productData['unit'], $productData['origin']);

            // Create product
            $product = Product::firstOrCreate(
                ['slug' => Str::slug($productData['name'])],
                array_merge($productData, [
                    'slug' => Str::slug($productData['name']),
                    'description' => 'منتج طازج عالي الجودة',
                    'category_id' => $category->id,
                    'subcategory_id' => $subcategory->id,
                    'market' => 'mart',
                    'is_active' => true,
                    'track_inventory' => false,
                ])
            );

            // Add unit attribute
            ProductAttribute::firstOrCreate(
                [
                    'product_id' => $product->id,
                    'name' => 'unit',
                ],
                [
                    'value' => $unit,
                    'value_text' => $unit,
                ]
            );

            // Add origin attribute
            ProductAttribute::firstOrCreate(
                [
                    'product_id' => $product->id,
                    'name' => 'origin',
                ],
                [
                    'value' => $origin,
                    'value_text' => $origin,
                ]
            );

            $this->command->info("Created weight-based product: {$product->name}");
        }

        $this->command->info('Weight-based products seeded successfully!');
    }
}

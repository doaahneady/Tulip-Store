<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some products to add variants to
        $products = \DB::table('products')->limit(5)->get();
        
        $variants = [];
        
        foreach ($products as $index => $product) {
            // Add size variants
            $sizes = ['Small', 'Medium', 'Large', 'XL'];
            $colors = ['Red', 'Blue', 'Black', 'White'];
            
            foreach ($sizes as $sizeIndex => $size) {
                foreach (array_slice($colors, 0, 2) as $colorIndex => $color) {
                    $variants[] = [
                        'product_id' => $product->id,
                        'name' => "$size - $color",
                        'sku' => 'VAR-' . $product->id . '-' . strtoupper(substr($size, 0, 1)) . strtoupper(substr($color, 0, 1)),
                        'price' => $product->price + ($sizeIndex * 5), // Larger sizes cost more
                        'stock' => rand(5, 50),
                        'attributes' => json_encode(['size' => $size, 'color' => $color]),
                        'image' => null,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            // Only add variants for first 3 products to keep it manageable
            if ($index >= 2) break;
        }
        
        \DB::table('product_variants')->insert($variants);
    }
}

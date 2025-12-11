<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories
        $categories = [
            [
                'name' => 'هدايا أطفال',
                'slug' => 'kids-gifts',
                'description' => 'هدايا مميزة للأطفال',
                'display_order' => 1,
            ],
            [
                'name' => 'سلال ورد',
                'slug' => 'flower-baskets',
                'description' => 'سلال ورد طبيعي',
                'display_order' => 2,
            ],
            [
                'name' => 'سلال فواكه',
                'slug' => 'fruit-baskets',
                'description' => 'سلال فواكه طازجة',
                'display_order' => 3,
            ],
            [
                'name' => 'عطور',
                'slug' => 'perfumes',
                'description' => 'عطور فاخرة',
                'display_order' => 4,
            ],
            [
                'name' => 'شوكولاتة',
                'slug' => 'chocolates',
                'description' => 'شوكولاتة فاخرة',
                'display_order' => 5,
            ],
            [
                'name' => 'تنسيق حفلات',
                'slug' => 'party-arrangements',
                'description' => 'تنسيق الحفلات والمناسبات',
                'display_order' => 6,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Create sample products
        $products = [
            // Kids Gifts
            [
                'name' => 'هدية طفل مميزة',
                'slug' => 'special-kid-gift',
                'description' => 'هدية مميزة للأطفال تحتوي على ألعاب وحلويات',
                'category_slug' => 'kids-gifts',
                'price' => 150.00,
                'discount_price' => 120.00,
                'stock' => 50,
                'rating' => 5,
                'reviews_count' => 23,
            ],
            [
                'name' => 'سلة ألعاب أطفال',
                'slug' => 'kids-toys-basket',
                'description' => 'سلة تحتوي على مجموعة من الألعاب التعليمية',
                'category_slug' => 'kids-gifts',
                'price' => 200.00,
                'stock' => 30,
                'rating' => 4,
                'reviews_count' => 15,
            ],
            // Flower Baskets
            [
                'name' => 'سلة ورد أحمر',
                'slug' => 'red-roses-basket',
                'description' => 'سلة ورد أحمر طبيعي',
                'category_slug' => 'flower-baskets',
                'price' => 250.00,
                'discount_price' => 220.00,
                'stock' => 20,
                'rating' => 5,
                'reviews_count' => 45,
            ],
            [
                'name' => 'سلة ورد مشكل',
                'slug' => 'mixed-flowers-basket',
                'description' => 'سلة ورد مشكل بألوان متعددة',
                'category_slug' => 'flower-baskets',
                'price' => 300.00,
                'stock' => 15,
                'rating' => 5,
                'reviews_count' => 32,
            ],
            // Fruit Baskets
            [
                'name' => 'سلة فواكه فاخرة',
                'slug' => 'luxury-fruit-basket',
                'description' => 'سلة فواكه طازجة ومتنوعة',
                'category_slug' => 'fruit-baskets',
                'price' => 180.00,
                'stock' => 25,
                'rating' => 4,
                'reviews_count' => 18,
            ],
            // Perfumes
            [
                'name' => 'عطر فاخر للرجال',
                'slug' => 'luxury-mens-perfume',
                'description' => 'عطر رجالي فاخر برائحة مميزة',
                'category_slug' => 'perfumes',
                'price' => 350.00,
                'discount_price' => 299.00,
                'stock' => 40,
                'rating' => 5,
                'reviews_count' => 67,
            ],
            [
                'name' => 'عطر نسائي راقي',
                'slug' => 'elegant-womens-perfume',
                'description' => 'عطر نسائي بعبوة أنيقة',
                'category_slug' => 'perfumes',
                'price' => 400.00,
                'stock' => 35,
                'rating' => 5,
                'reviews_count' => 89,
            ],
            // Chocolates
            [
                'name' => 'علبة شوكولاتة بلجيكية',
                'slug' => 'belgian-chocolate-box',
                'description' => 'شوكولاتة بلجيكية فاخرة',
                'category_slug' => 'chocolates',
                'price' => 120.00,
                'discount_price' => 99.00,
                'stock' => 60,
                'rating' => 5,
                'reviews_count' => 102,
            ],
            // Party Arrangements
            [
                'name' => 'تنسيق حفلة عيد ميلاد',
                'slug' => 'birthday-party-arrangement',
                'description' => 'تنسيق كامل لحفلة عيد ميلاد',
                'category_slug' => 'party-arrangements',
                'price' => 500.00,
                'discount_price' => 450.00,
                'stock' => 10,
                'rating' => 5,
                'reviews_count' => 28,
            ],
        ];

        foreach ($products as $productData) {
            $category = Category::where('slug', $productData['category_slug'])->first();
            
            if ($category) {
                unset($productData['category_slug']);
                $productData['category_id'] = $category->id;
                
                Product::firstOrCreate(
                    ['slug' => $productData['slug']],
                    $productData
                );
            }
        }
    }
}

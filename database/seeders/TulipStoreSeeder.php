<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Seeder;

class TulipStoreSeeder extends Seeder
{
    public function run(): void
    {
    

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Create products with robust category mapping and market flag
        $storeId = Store::where('slug', 'sample-store')->value('id');
        $categoryOrder = [
            1 => 'fresh-flowers',
            2 => 'gifts',
            3 => 'chocolates',
            4 => 'balloons',
        ];
        $slugToId = Category::whereIn('slug', array_values($categoryOrder))->pluck('id', 'slug');
        $products = [
            // Fresh Flowers
            [
                'name' => 'باقة الورود الحمراء الفاخرة',
                'slug' => 'red-roses-premium',
                'description' => 'باقة جميلة من 24 وردة حمراء طازة',
                'details' => 'تحتوي على: 24 وردة حمراء طازة، أوراق خضراء مختارة، تغليف فاخر',
                'category_id' => 1,
                'price' => 299.99,
                'discount_price' => null,
                'stock' => 50,
                'rating' => 5,
                'reviews_count' => 45,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'باقة الزهور المتعددة الألوان',
                'slug' => 'mixed-flowers',
                'description' => 'مزيج جميل من الزهور الملونة',
                'details' => 'تحتوي على: ورود، ستاتس، يوكاليبتس، تغليف فاخر',
                'category_id' => 1,
                'price' => 199.99,
                'discount_price' => 149.99,
                'stock' => 60,
                'rating' => 4,
                'reviews_count' => 28,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'باقة الزنبق البيضاء',
                'slug' => 'white-lilies',
                'description' => 'باقة أنيقة من الزنبق الأبيض',
                'category_id' => 1,
                'price' => 249.99,
                'stock' => 40,
                'rating' => 5,
                'reviews_count' => 22,
                'is_featured' => false,
                'is_active' => true,
            ],

            // Gifts
            [
                'name' => 'صندوق الهدايا الذهبي',
                'slug' => 'gold-gift-box',
                'description' => 'صندوق هدايا فاخر بألوان ذهبية',
                'category_id' => 2,
                'price' => 149.99,
                'stock' => 30,
                'rating' => 4,
                'reviews_count' => 15,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'مجموعة الشموع العطرية',
                'slug' => 'scented-candles',
                'description' => 'مجموعة شموع عطرية فاخرة',
                'category_id' => 2,
                'price' => 99.99,
                'discount_price' => 79.99,
                'stock' => 50,
                'rating' => 5,
                'reviews_count' => 34,
                'is_featured' => false,
                'is_active' => true,
            ],

            // Chocolates
            [
                'name' => 'صندوق الشوكولاتة البلجيكية الفاخرة',
                'slug' => 'belgian-chocolates',
                'description' => 'شوكولاتة بلجيكية عالية الجودة',
                'details' => 'تحتوي على 20 قطعة شوكولاتة بنكهات متنوعة',
                'category_id' => 3,
                'price' => 179.99,
                'stock' => 40,
                'rating' => 5,
                'reviews_count' => 56,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'حلويات الفواكه الطازة',
                'slug' => 'fruit-sweets',
                'description' => 'حلويات لذيذة بنكهات الفواكه',
                'category_id' => 3,
                'price' => 89.99,
                'stock' => 45,
                'rating' => 4,
                'reviews_count' => 18,
                'is_featured' => false,
                'is_active' => true,
            ],

            // Balloons
            [
                'name' => 'باقة البالونات الملونة',
                'slug' => 'colorful-balloons',
                'description' => 'بالونات ملونة للحفلات والمناسبات',
                'category_id' => 4,
                'price' => 69.99,
                'stock' => 100,
                'rating' => 4,
                'reviews_count' => 12,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'بالونات الهيليوم الفضية',
                'slug' => 'silver-helium-balloons',
                'description' => 'بالونات هيليوم فضية أنيقة',
                'category_id' => 4,
                'price' => 119.99,
                'discount_price' => 99.99,
                'stock' => 60,
                'rating' => 5,
                'reviews_count' => 27,
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            $data = $productData;
            // Map numeric category placeholders to actual IDs by slug
            $placeholder = (int) ($data['category_id'] ?? 0);
            if ($placeholder >= 1 && $placeholder <= 4) {
                $slug = $categoryOrder[$placeholder];
                $data['category_id'] = $slugToId[$slug] ?? $data['category_id'];
            }
            if (isset($data['image'])) {
                $data['images'] = [$data['image']];
                unset($data['image']);
            }
            $data['sku'] = strtoupper(substr($data['slug'], 0, 3)).'-'.str_pad(random_int(1, 999), 3, '0', STR_PAD_LEFT);
            $data['market'] = 'store';
            if ($storeId) {
                $data['store_id'] = $storeId;
            }
            Product::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        $this->command->info('Tulip Store data seeded successfully!');
    }
}

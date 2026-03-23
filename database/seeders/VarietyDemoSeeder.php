<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Gift;
use App\Models\GiftBox;
use App\Models\GiftCard;
use App\Models\GiftFiller;
use App\Models\GiftRibbon;
use App\Models\GiftWrapping;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class VarietyDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedStore();
        $this->seedMart();
        $this->seedGifts();
    }

    private function seedStore(): void
    {
        if (! Schema::hasTable('categories') || ! Schema::hasTable('products')) {
            return;
        }

        $storeCategories = [
            ['name' => 'هدايا فاخرة', 'slug' => 'luxury-gifts', 'description' => 'هدايا مميزة وفاخرة', 'image' => 'https://via.placeholder.com/300x300?text=Luxury', 'display_order' => 1, 'market' => 'store', 'is_active' => true],
            ['name' => 'ورد وباقات', 'slug' => 'bouquets', 'description' => 'باقات ورد أنيقة', 'image' => 'https://via.placeholder.com/300x300?text=Bouquets', 'display_order' => 2, 'market' => 'store', 'is_active' => true],
            ['name' => 'حلويات وشوكولاتة', 'slug' => 'sweets', 'description' => 'حلويات وشوكولاتة فاخرة', 'image' => 'https://via.placeholder.com/300x300?text=Sweets', 'display_order' => 3, 'market' => 'store', 'is_active' => true],
        ];
        foreach ($storeCategories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        $storeCatIds = Category::where('market', 'store')->pluck('id', 'slug');
        $storeProducts = [
            ['name' => 'باقة التوليب البيضاء', 'slug' => 'white-tulip', 'description' => 'باقة توليب أبيض أنيقة', 'category_slug' => 'bouquets', 'price' => 189.00, 'discount_price' => 159.00, 'stock_quantity' => 24, 'is_featured' => true, 'is_active' => true, 'market' => 'store'],
            ['name' => 'صندوق هدايا فاخر ذهبي', 'slug' => 'gold-lux-box', 'description' => 'صندوق ذهبي بتغليف أنيق', 'category_slug' => 'luxury-gifts', 'price' => 249.00, 'stock_quantity' => 15, 'is_featured' => true, 'is_active' => true, 'market' => 'store'],
            ['name' => 'شوكولاتة داكنة بلجيكية', 'slug' => 'dark-belgian-choco', 'description' => 'شوكولاتة بلجيكية 70%', 'category_slug' => 'sweets', 'price' => 49.00, 'discount_price' => 39.00, 'stock_quantity' => 60, 'is_active' => true, 'market' => 'store'],
        ];
        foreach ($storeProducts as $p) {
            $catId = $storeCatIds[$p['category_slug']] ?? null;
            if (! $catId) {
                continue;
            }
            $data = $p;
            unset($data['category_slug']);
            $data['category_id'] = $catId;
            Product::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }

    private function seedMart(): void
    {
        if (! Schema::hasTable('categories') || ! Schema::hasTable('products')) {
            return;
        }

        $martCategories = [
            ['name' => 'فواكه', 'slug' => 'fruits', 'description' => 'فواكه طازجة يومياً', 'image' => null, 'display_order' => 1, 'market' => 'mart', 'is_active' => true],
            ['name' => 'خضار', 'slug' => 'vegetables', 'description' => 'خضروات موسمية طازجة', 'image' => null, 'display_order' => 2, 'market' => 'mart', 'is_active' => true],
            ['name' => 'ألبان', 'slug' => 'dairy', 'description' => 'أجبان وألبان', 'image' => null, 'display_order' => 3, 'market' => 'mart', 'is_active' => true],
            ['name' => 'مخبوزات', 'slug' => 'bakery', 'description' => 'خبز ومخبوزات طازجة', 'image' => null, 'display_order' => 4, 'market' => 'mart', 'is_active' => true],
        ];
        foreach ($martCategories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        $catIds = Category::where('market', 'mart')->pluck('id', 'slug');
        $variety = [
            // fruits
            ['name' => 'تفاح لبناني', 'slug' => 'apple-lebanese', 'category' => 'fruits', 'price' => 8.50, 'discount_price' => null, 'stock_quantity' => 120, 'is_featured' => false, 'origin' => 'لبناني', 'unit' => 'كغ'],
            ['name' => 'موز إكوادوري', 'slug' => 'banana-ecuador', 'category' => 'fruits', 'price' => 7.20, 'discount_price' => 6.50, 'stock_quantity' => 200, 'is_featured' => true, 'origin' => 'مستورد', 'unit' => 'كغ'],
            ['name' => 'برتقال مصري', 'slug' => 'orange-egypt', 'category' => 'fruits', 'price' => 5.90, 'discount_price' => null, 'stock_quantity' => 150, 'is_featured' => false, 'origin' => 'مصري', 'unit' => 'كغ'],
            // vegetables
            ['name' => 'طماطم بلدي', 'slug' => 'tomato-local', 'category' => 'vegetables', 'price' => 4.00, 'discount_price' => 3.50, 'stock_quantity' => 180, 'is_featured' => false, 'origin' => 'محلي', 'unit' => 'كغ'],
            ['name' => 'خيار صوبي', 'slug' => 'cucumber-greenhouse', 'category' => 'vegetables', 'price' => 6.25, 'discount_price' => null, 'stock_quantity' => 140, 'is_featured' => true, 'origin' => 'محلي', 'unit' => 'كغ'],
            ['name' => 'بطاطا سورية', 'slug' => 'potato-syrian', 'category' => 'vegetables', 'price' => 3.20, 'discount_price' => null, 'stock_quantity' => 300, 'is_featured' => false, 'origin' => 'محلي', 'unit' => 'كغ'],
            // dairy
            ['name' => 'حليب بقر طازج', 'slug' => 'fresh-milk', 'category' => 'dairy', 'price' => 9.90, 'discount_price' => null, 'stock_quantity' => 80, 'is_featured' => true, 'origin' => 'محلي', 'unit' => 'لتر'],
            ['name' => 'لبنة بقريّة', 'slug' => 'labneh', 'category' => 'dairy', 'price' => 14.00, 'discount_price' => 12.50, 'stock_quantity' => 60, 'is_featured' => false, 'origin' => 'محلي', 'unit' => 'كغ'],
            // bakery
            ['name' => 'خبز عربي', 'slug' => 'arabic-bread', 'category' => 'bakery', 'price' => 2.00, 'discount_price' => null, 'stock_quantity' => 400, 'is_featured' => true, 'origin' => 'محلي', 'unit' => 'ربطة'],
            ['name' => 'كرواسون زبدة', 'slug' => 'butter-croissant', 'category' => 'bakery', 'price' => 3.50, 'discount_price' => null, 'stock_quantity' => 90, 'is_featured' => false, 'origin' => 'محلي', 'unit' => 'قطعة'],
        ];

        foreach ($variety as $p) {
            $categorySlug = $p['category'];
            $categoryId = $catIds[$categorySlug] ?? null;
            if (! $categoryId) {
                continue;
            }
            $data = [
                'name' => $p['name'],
                'slug' => $p['slug'],
                'description' => $p['name'],
                'category_id' => $categoryId,
                'price' => $p['price'],
                'discount_price' => $p['discount_price'],
                'stock_quantity' => $p['stock_quantity'],
                'is_featured' => $p['is_featured'],
                'is_active' => true,
                'market' => 'mart',
            ];
            $product = Product::updateOrCreate(['slug' => $data['slug']], $data);
            // Attach attributes
            ProductAttribute::updateOrCreate(
                ['product_id' => $product->id, 'name' => 'unit'],
                ['value' => $p['unit']]
            );
            ProductAttribute::updateOrCreate(
                ['product_id' => $product->id, 'name' => 'origin'],
                ['value' => $p['origin']]
            );
        }
    }

    private function seedGifts(): void
    {
        if (! Schema::hasTable('gift_boxes')) {
            return;
        }

        // Boxes (also reused as bouquet sizes by context in UI)
        $boxes = [
            ['name' => 'صندوق صغير', 'size' => 'small', 'price' => 49, 'max_items' => 6, 'stock' => 50, 'is_active' => true, 'sort_order' => 1],
            ['name' => 'صندوق متوسط', 'size' => 'medium', 'price' => 79, 'max_items' => 12, 'stock' => 40, 'is_active' => true, 'sort_order' => 2],
            ['name' => 'صندوق كبير', 'size' => 'large', 'price' => 119, 'max_items' => 18, 'stock' => 30, 'is_active' => true, 'sort_order' => 3],
            ['name' => 'صندوق فاخر XL', 'size' => 'xl', 'price' => 179, 'max_items' => 24, 'stock' => 20, 'is_active' => true, 'sort_order' => 4],
        ];
        foreach ($boxes as $b) {
            GiftBox::updateOrCreate(['size' => $b['size'], 'name' => $b['name']], $b);
        }

        // Fillers (flowers + others)
        $fillers = [
            ['name' => 'ورد أحمر', 'category' => 'flower', 'price' => 8, 'stock' => 200, 'is_active' => true, 'sort_order' => 1],
            ['name' => 'ورد أبيض', 'category' => 'flower', 'price' => 7, 'stock' => 180, 'is_active' => true, 'sort_order' => 2],
            ['name' => 'توليب وردي', 'category' => 'flower', 'price' => 10, 'stock' => 150, 'is_active' => true, 'sort_order' => 3],
            ['name' => 'شوكولاتة داكنة', 'category' => 'chocolate', 'price' => 25, 'stock' => 120, 'is_active' => true, 'sort_order' => 4],
            ['name' => 'دبدوب صغير', 'category' => 'accessory', 'price' => 35, 'stock' => 60, 'is_active' => true, 'sort_order' => 5],
            ['name' => 'بالون هيليوم', 'category' => 'other', 'price' => 15, 'stock' => 80, 'is_active' => true, 'sort_order' => 6],
        ];
        foreach ($fillers as $f) {
            GiftFiller::updateOrCreate(['name' => $f['name']], $f);
        }

        // Wrappings
        if (Schema::hasTable('gift_wrappings')) {
            $wrappings = [
                ['name' => 'ورق كرافت', 'price' => 0, 'color' => 'بني', 'pattern' => 'سادة', 'is_active' => true, 'sort_order' => 1],
                ['name' => 'ورق وردي', 'price' => 10, 'color' => 'وردي', 'pattern' => 'سادة', 'is_active' => true, 'sort_order' => 2],
                ['name' => 'ورق ذهبي', 'price' => 15, 'color' => 'ذهبي', 'pattern' => 'لامع', 'is_active' => true, 'sort_order' => 3],
            ];
            foreach ($wrappings as $w) {
                GiftWrapping::updateOrCreate(['name' => $w['name']], $w);
            }
        }

        // Ribbons
        if (Schema::hasTable('gift_ribbons')) {
            $ribbons = [
                ['name' => 'شريط أحمر', 'price' => 5, 'color' => 'أحمر', 'is_active' => true, 'sort_order' => 1],
                ['name' => 'شريط ذهبي', 'price' => 7, 'color' => 'ذهبي', 'is_active' => true, 'sort_order' => 2],
                ['name' => 'شريط أبيض', 'price' => 5, 'color' => 'أبيض', 'is_active' => true, 'sort_order' => 3],
            ];
            foreach ($ribbons as $r) {
                GiftRibbon::updateOrCreate(['name' => $r['name']], $r);
            }
        }

        // Cards
        if (Schema::hasTable('gift_cards')) {
            $cards = [
                ['name' => 'بطاقة حب', 'occasion' => 'valentine', 'price' => 5, 'is_active' => true, 'sort_order' => 1],
                ['name' => 'بطاقة عيد ميلاد', 'occasion' => 'birthday', 'price' => 5, 'is_active' => true, 'sort_order' => 2],
                ['name' => 'بطاقة تهنئة', 'occasion' => 'congrats', 'price' => 5, 'is_active' => true, 'sort_order' => 3],
            ];
            foreach ($cards as $c) {
                GiftCard::updateOrCreate(['name' => $c['name']], $c);
            }
        }

        // Featured ready gifts variety (only if gifts table exists but leave GiftSeeder as well)
        if (Schema::hasTable('gifts')) {
            $varietyGifts = [
                [
                    'name' => 'باقة رومانسية صغيرة',
                    'description' => 'باقة أنيقة من الورود الصغيرة مع تغليف بسيط ورسالة قصيرة.',
                    'price' => 129,
                    'category' => 'valentine',
                    'occasion' => 'عيد الحب',
                    'images' => ['/images/valentine_gift.png'],
                    'size' => 'small',
                    'is_featured' => true,
                    'is_active' => true,
                    'stock_quantity' => 10,
                ],
                [
                    'name' => 'باقة تخرج بيضاء',
                    'description' => 'باقة زهور بيضاء للتخرج بتنسيق راقٍ وبطاقة تهنئة.',
                    'price' => 179,
                    'category' => 'graduation',
                    'occasion' => 'تخرج',
                    'images' => ['/images/graduation.png'],
                    'size' => 'medium',
                    'is_featured' => false,
                    'is_active' => true,
                    'stock_quantity' => 14,
                ],
            ];
            foreach ($varietyGifts as $g) {
                Gift::updateOrCreate(['name' => $g['name']], $g);
            }
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use App\Models\Product;
use Illuminate\Http\Request;

class Store3DController extends Controller
{
    public function index()
    {
        return view('store-3d');
    }

    public function getSectionProducts(Request $request, $section)
    {
        $products = collect();

        switch ($section) {
            case 'gifts':
                $gifts = Gift::active()->take(20)->get();
                $products = $gifts->map(function ($gift) {
                    return (object) [
                        'id' => $gift->id,
                        'name' => $gift->name,
                        'price' => $gift->price,
                        'formatted_price' => $gift->formatted_price,
                        'image' => $gift->main_image,
                        'type' => 'gift',
                    ];
                });
                break;

            case 'flowers':
                $products = $this->getMockProducts('flowers', '🌹', [
                    'باقة ورود حمراء', 'باقة زهور مختلطة', 'وردة بيضاء', 'باقة عرس',
                    'زهور التوليب', 'باقة عيد الحب', 'ورود صفراء', 'زهور الياسمين',
                ]);
                break;

            case 'electronics':
                $products = $this->getMockProducts('electronics', '📱', [
                    'هاتف ذكي', 'لابتوب', 'سماعات لاسلكية', 'ساعة ذكية',
                    'تابلت', 'كاميرا رقمية', 'شاشة كمبيوتر', 'مكبر صوت',
                ]);
                break;

            case 'fashion':
                $products = $this->getMockProducts('fashion', '👗', [
                    'فستان أنيق', 'قميص رجالي', 'حقيبة يد', 'حذاء رياضي',
                    'جاكيت شتوي', 'تنورة', 'بنطلون جينز', 'قبعة',
                ]);
                break;

            case 'home':
                $products = $this->getMockProducts('home', '🏠', [
                    'وسادة مريحة', 'مصباح طاولة', 'مزهرية', 'ستائر',
                    'سجادة', 'مرآة حائط', 'ساعة حائط', 'شمعة معطرة',
                ]);
                break;

            case 'books':
                $products = $this->getMockProducts('books', '📚', [
                    'رواية عربية', 'كتاب طبخ', 'كتاب تطوير الذات', 'قاموس',
                    'كتاب أطفال', 'كتاب تاريخ', 'مجلة', 'كتاب شعر',
                ]);
                break;

            case 'toys':
                $products = $this->getMockProducts('toys', '🧸', [
                    'دبدوب', 'لعبة تركيب', 'كرة قدم', 'دراجة أطفال',
                    'لعبة إلكترونية', 'ألوان', 'لعبة تعليمية', 'دمية',
                ]);
                break;

            case 'beauty':
                $products = $this->getMockProducts('beauty', '💄', [
                    'كريم مرطب', 'أحمر شفاه', 'عطر نسائي', 'شامبو',
                    'مكياج', 'كريم أساس', 'ماسك للوجه', 'زيت عطري',
                ]);
                break;

            default:
                // Get all products
                $allProducts = Product::take(50)->get();
                $allGifts = Gift::active()->take(10)->get();

                $products = $allProducts->map(function ($product) {
                    return (object) [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'formatted_price' => number_format($product->price, 2).' ل.س',
                        'image' => $product->image,
                        'type' => 'product',
                    ];
                });

                $giftProducts = $allGifts->map(function ($gift) {
                    return (object) [
                        'id' => $gift->id,
                        'name' => $gift->name,
                        'price' => $gift->price,
                        'formatted_price' => $gift->formatted_price,
                        'image' => $gift->main_image,
                        'type' => 'gift',
                    ];
                });

                $products = $products->concat($giftProducts);
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $products->values()->all(),
        ]);
    }

    private function getMockProducts($category, $icon, $names)
    {
        $products = collect();

        foreach ($names as $index => $name) {
            $products->push((object) [
                'id' => $category.'_'.($index + 1),
                'name' => $name,
                'price' => rand(50, 500),
                'formatted_price' => rand(50, 500).'ل.س',
                'image' => null,
                'icon' => $icon,
                'type' => 'mock',
            ]);
        }

        return $products;
    }

    public function getSectionCounts()
    {
        $counts = [
            'gifts' => Gift::active()->count(),
            'total' => Product::count() + Gift::active()->count(),
            'flowers' => rand(20, 70),
            'electronics' => rand(50, 150),
            'fashion' => rand(30, 110),
            'home' => rand(25, 85),
            'books' => rand(15, 55),
            'toys' => rand(35, 105),
            'beauty' => rand(20, 65),
        ];

        return response()->json([
            'success' => true,
            'data' => $counts,
        ]);
    }
}

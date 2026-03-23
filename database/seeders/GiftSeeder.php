<?php

namespace Database\Seeders;

use App\Models\Gift;
use Illuminate\Database\Seeder;

class GiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gifts = [
            [
                'name' => 'باقة ورود حمراء رومانسية',
                'description' => 'باقة رائعة من الورود الحمراء الطازجة مع تغليف أنيق، مثالية للتعبير عن الحب والرومانسية في المناسبات الخاصة.',
                'price' => 150.00,
                'category' => 'valentine',
                'occasion' => 'عيد الحب',
                'images' => ['/images/valentine_gift.png'],
                'size' => 'medium',
                'is_customizable' => true,
                'customization_options' => ['رسالة شخصية', 'لون التغليف', 'إضافة شوكولاتة'],
                'stock_quantity' => 25,
                'is_featured' => true,
                'delivery_time' => 'نفس اليوم',
                'rating' => 4.8,
                'reviews_count' => 42,
            ],
            [
                'name' => 'صندوق شوكولاتة فاخر',
                'description' => 'مجموعة مختارة من أفخر أنواع الشوكولاتة البلجيكية في صندوق أنيق، هدية مثالية لعشاق الحلويات.',
                'price' => 120.00,
                'category' => 'birthday',
                'occasion' => 'عيد ميلاد',
                'images' => ['/images/graduation.png'],
                'size' => 'small',
                'is_customizable' => true,
                'customization_options' => ['نوع الشوكولاتة', 'رسالة على الصندوق'],
                'stock_quantity' => 30,
                'is_featured' => true,
                'delivery_time' => 'خلال 24 ساعة',
                'rating' => 4.9,
                'reviews_count' => 67,
            ],
            [
                'name' => 'سلة فواكه طازجة',
                'description' => 'سلة مليئة بأطيب وأجود أنواع الفواكه الطازجة والموسمية، مرتبة بشكل جميل ومغلفة بأناقة.',
                'price' => 80.00,
                'category' => 'general',
                'occasion' => 'زيارة',
                'images' => ['/images/graduation.png'],
                'size' => 'large',
                'is_customizable' => false,
                'stock_quantity' => 20,
                'is_featured' => false,
                'delivery_time' => 'نفس اليوم',
                'rating' => 4.5,
                'reviews_count' => 28,
            ],
         
           
        ];

        foreach ($gifts as $gift) {
            Gift::create($gift);
        }
    }
}

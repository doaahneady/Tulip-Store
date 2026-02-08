<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'value' => 10.00,
                'min_purchase' => 50.00,
                'max_usage' => 100,
                'used_count' => 15,
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
                'description' => 'خصم 10% للعملاء الجدد - الحد الأدنى للشراء 50$',
                'applicable_products' => null,
                'applicable_categories' => null,
            ],
            [
                'code' => 'SAVE20',
                'type' => 'fixed',
                'value' => 20.00,
                'min_purchase' => 100.00,
                'max_usage' => 50,
                'used_count' => 8,
                'expires_at' => now()->addMonths(2),
                'is_active' => true,
                'description' => 'خصم 20$ على الطلبات فوق 100$',
                'applicable_products' => null,
                'applicable_categories' => null,
            ],
            [
                'code' => 'FREESHIP',
                'type' => 'free_shipping',
                'value' => 0.00,
                'min_purchase' => 75.00,
                'max_usage' => 200,
                'used_count' => 45,
                'expires_at' => now()->addMonths(1),
                'is_active' => true,
                'description' => 'شحن مجاني للطلبات فوق 75$',
                'applicable_products' => null,
                'applicable_categories' => null,
            ],
            [
                'code' => 'SUMMER25',
                'type' => 'percentage',
                'value' => 25.00,
                'min_purchase' => 150.00,
                'max_usage' => 30,
                'used_count' => 12,
                'expires_at' => now()->addDays(30),
                'is_active' => true,
                'description' => 'عرض الصيف - خصم 25% على الطلبات فوق 150$',
                'applicable_products' => null,
                'applicable_categories' => null,
            ],
            [
                'code' => 'VIP50',
                'type' => 'fixed',
                'value' => 50.00,
                'min_purchase' => 200.00,
                'max_usage' => 10,
                'used_count' => 3,
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
                'description' => 'كوبون VIP - خصم 50$ للعملاء المميزين',
                'applicable_products' => null,
                'applicable_categories' => null,
            ],
            [
                'code' => 'EXPIRED',
                'type' => 'percentage',
                'value' => 15.00,
                'min_purchase' => 50.00,
                'max_usage' => 100,
                'used_count' => 100,
                'expires_at' => now()->subDays(10),
                'is_active' => false,
                'description' => 'كوبون منتهي الصلاحية',
                'applicable_products' => null,
                'applicable_categories' => null,
            ],
        ];

        foreach ($coupons as $coupon) {
            \DB::table('coupons')->updateOrInsert(
                ['code' => $coupon['code']],
                array_merge($coupon, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing notifications
        Notification::truncate();
        
        $user = User::first();
        
        if (!$user) {
            echo "⚠️ No users found. Please create a user first.\n";
            return;
        }
        
        $notifications = [
            [
                'type' => 'order_created',
                'title' => 'تم إنشاء طلبك بنجاح',
                'message' => 'تم استلام طلبك رقم ORD-123456 وسيتم معالجته قريباً. شكراً لثقتك بنا!',
                'icon' => 'fas fa-shopping-cart',
                'color' => 'blue',
                'link' => '/my-orders',
                'is_read' => false
            ],
            [
                'type' => 'order_confirmed',
                'title' => 'تم تأكيد طلبك',
                'message' => 'طلبك رقم ORD-123456 تم تأكيده وسيتم شحنه خلال 24 ساعة.',
                'icon' => 'fas fa-check-circle',
                'color' => 'green',
                'link' => '/my-orders',
                'is_read' => false
            ],
            [
                'type' => 'order_shipped',
                'title' => 'تم شحن طلبك',
                'message' => 'طلبك رقم ORD-123456 في الطريق إليك! سيصل خلال 2-3 أيام عمل.',
                'icon' => 'fas fa-shipping-fast',
                'color' => 'orange',
                'link' => '/my-orders',
                'is_read' => false
            ],
            [
                'type' => 'order_delivered',
                'title' => 'تم توصيل طلبك',
                'message' => 'طلبك رقم ORD-123456 تم توصيله بنجاح. نتمنى أن تكون راضياً عن مشترياتك!',
                'icon' => 'fas fa-check-double',
                'color' => 'green',
                'link' => '/my-orders',
                'is_read' => true
            ],
            [
                'type' => 'welcome',
                'title' => 'مرحباً بك في Tulip Store',
                'message' => 'نحن سعداء لانضمامك إلينا! استكشف مجموعتنا الواسعة من المنتجات عالية الجودة.',
                'icon' => 'fas fa-heart',
                'color' => 'red',
                'link' => '/',
                'is_read' => true
            ],
            [
                'type' => 'promotion',
                'title' => 'عرض خاص لك!',
                'message' => 'خصم 20% على جميع المنتجات! استخدم كود SAVE20 عند الدفع. العرض ساري حتى نهاية الشهر.',
                'icon' => 'fas fa-gift',
                'color' => 'orange',
                'link' => '/',
                'is_read' => false
            ],
            [
                'type' => 'new_product',
                'title' => 'منتجات جديدة وصلت!',
                'message' => 'تحقق من أحدث إضافاتنا من الهدايا الفاخرة وباقات الورود الرائعة.',
                'icon' => 'fas fa-star',
                'color' => 'blue',
                'link' => '/',
                'is_read' => false
            ],
            [
                'type' => 'order_cancelled',
                'title' => 'تم إلغاء طلبك',
                'message' => 'للأسف، تم إلغاء طلبك رقم ORD-789012. سيتم إرجاع المبلغ خلال 3-5 أيام عمل.',
                'icon' => 'fas fa-times-circle',
                'color' => 'red',
                'link' => '/my-orders',
                'is_read' => false
            ]
        ];
        
        foreach ($notifications as $index => $notificationData) {
            Notification::create([
                'user_id' => $user->id,
                'type' => $notificationData['type'],
                'title' => $notificationData['title'],
                'message' => $notificationData['message'],
                'icon' => $notificationData['icon'],
                'color' => $notificationData['color'],
                'link' => $notificationData['link'],
                'is_read' => $notificationData['is_read'],
                'created_at' => now()->subHours($index * 3),
                'updated_at' => now()->subHours($index * 3)
            ]);
            
            echo "✅ Created notification: {$notificationData['title']}\n";
        }
        
        echo "\n🎉 Successfully created 8 test notifications!\n";
    }
}

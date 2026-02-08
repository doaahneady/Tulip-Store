<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            echo "No users found. Please create users first.\n";

            return;
        }

        $notifications = [
            [
                'type' => 'order_created',
                'title' => 'تم إنشاء طلب جديد',
                'message' => 'تم إنشاء طلبك بنجاح! رقم الطلب: #ORD-12345',
                'icon' => 'fas fa-shopping-cart',
                'color' => 'blue',
                'link' => '/orders',
            ],
            [
                'type' => 'order_confirmed',
                'title' => 'تم تأكيد طلبك',
                'message' => 'تم تأكيد طلبك وجاري تجهيزه للشحن',
                'icon' => 'fas fa-check-circle',
                'color' => 'green',
                'link' => '/orders',
            ],
            [
                'type' => 'order_shipped',
                'title' => 'تم شحن طلبك',
                'message' => 'طلبك في الطريق إليك! سيصل خلال 2-3 أيام',
                'icon' => 'fas fa-truck',
                'color' => 'blue',
                'link' => '/orders',
            ],
            [
                'type' => 'order_delivered',
                'title' => 'تم توصيل طلبك',
                'message' => 'تم توصيل طلبك بنجاح! نتمنى أن تستمتع بمشترياتك',
                'icon' => 'fas fa-box-open',
                'color' => 'green',
                'link' => '/orders',
            ],
            [
                'type' => 'promotion',
                'title' => 'عرض خاص لك!',
                'message' => 'خصم 30% على جميع المنتجات! العرض ساري لمدة 48 ساعة فقط',
                'icon' => 'fas fa-gift',
                'color' => 'orange',
                'link' => '/store',
            ],
            [
                'type' => 'new_product',
                'title' => 'منتجات جديدة',
                'message' => 'تم إضافة منتجات جديدة إلى المتجر. تفقدها الآن!',
                'icon' => 'fas fa-sparkles',
                'color' => 'blue',
                'link' => '/store',
            ],
            [
                'type' => 'welcome',
                'title' => 'مرحباً بك في Tulip Store',
                'message' => 'نحن سعداء بانضمامك إلينا! استمتع بتجربة تسوق رائعة',
                'icon' => 'fas fa-heart',
                'color' => 'red',
                'link' => '/store',
            ],
            [
                'type' => 'low_stock',
                'title' => 'تنبيه: مخزون منخفض',
                'message' => 'المنتج الذي أضفته إلى قائمة الرغبات أوشك على النفاذ!',
                'icon' => 'fas fa-exclamation-triangle',
                'color' => 'orange',
                'link' => '/store',
            ],
        ];

        foreach ($users as $user) {
            // Create 3-5 notifications per user
            $count = rand(3, 5);

            for ($i = 0; $i < $count; $i++) {
                $notification = $notifications[array_rand($notifications)];
                $createdAt = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23));

                // 60% chance of being read
                $isRead = rand(1, 100) <= 60;

                Notification::create([
                    'user_id' => $user->id,
                    'type' => $notification['type'],
                    'title' => $notification['title'],
                    'message' => $notification['message'],
                    'icon' => $notification['icon'],
                    'color' => $notification['color'],
                    'link' => $notification['link'],
                    'is_read' => $isRead,
                    'read_at' => $isRead ? $createdAt->copy()->addHours(rand(1, 48)) : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }

        echo "Successfully created test notifications for all users!\n";
        echo 'Total notifications: '.Notification::count()."\n";
    }
}

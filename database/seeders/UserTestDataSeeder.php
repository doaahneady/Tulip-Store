<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTestDataSeeder extends Seeder
{
    public function run(): void
    {
        $arabicNames = [
            'أحمد محمد',
            'فاطمة علي',
            'محمد عبدالله',
            'نورة سعد',
            'خالد إبراهيم',
            'سارة أحمد',
            'عبدالرحمن خالد',
            'مريم حسن',
            'يوسف عمر',
            'هند محمود',
            'عمر فهد',
            'ريم عبدالعزيز',
            'سلطان ناصر',
            'لينا سليمان',
            'فيصل راشد',
            'دانة عبدالله',
            'طارق سعيد',
            'جواهر فيصل',
            'ماجد عبدالرحمن',
            'شهد يوسف',
        ];

        foreach ($arabicNames as $index => $name) {
            $email = 'user'.($index + 1).'@example.com';

            // Skip if user already exists
            if (User::where('email', $email)->exists()) {
                continue;
            }

            // Random registration date in the last 6 months
            $createdAt = Carbon::now()->subDays(rand(1, 180));

            User::create([
                'name' => $name,
                'username' => 'user'.($index + 1),
                'email' => $email,
                'password' => Hash::make('password123'),
                'phone' => '05'.rand(10000000, 99999999),
                'is_admin' => false,
                'role_id' => null,
                'email_verified_at' => $createdAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        echo 'Successfully created '.count($arabicNames)." test users!\n";
        echo 'Total users: '.User::count()."\n";
    }
}

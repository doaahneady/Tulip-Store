<?php

namespace App\Console\Commands;

use App\Mail\BirthdayWishMail;
use App\Models\DiscountCoupon;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class GenerateBirthdayCoupons extends Command
{
    protected $signature = 'coupons:generate-birthday';
    protected $description = 'Generate birthday coupons for users whose birthday is today';

    public function handle()
    {
        $today = now();
        $todayMonth = $today->month;
        $todayDay = $today->day;

        // Find users whose birthday is today
        $users = User::whereNotNull('birth_date')
            ->whereNotNull('email')
            ->whereRaw('MONTH(birth_date) = ?', [$todayMonth])
            ->whereRaw('DAY(birth_date) = ?', [$todayDay])
            ->get();

        if ($users->isEmpty()) {
            $this->info('No birthdays today.');
            return 0;
        }

        $this->info("Found {$users->count()} birthday(s) today!");

        foreach ($users as $user) {
            // Generate random discount between 5% and 10%
            $discountPercentage = rand(5, 10);

            // Generate unique coupon code
            $couponCode = 'BIRTHDAY' . strtoupper(Str::random(6));

            // Check if user already has a birthday coupon for today
            $existingCoupon = DiscountCoupon::where('code', 'LIKE', 'BIRTHDAY%')
                ->where('user_id', $user->id)
                ->whereDate('valid_from', $today->toDateString())
                ->first();

            if ($existingCoupon) {
                $this->warn("User {$user->name} already has a birthday coupon today.");
                continue;
            }

            // Create the birthday coupon
            $coupon = DiscountCoupon::create([
                'code' => $couponCode,
                'type' => 'percentage',
                'value' => $discountPercentage,
                'min_order_amount' => 0,
                'max_discount_amount' => null,
                'usage_limit' => 1,
                'usage_count' => 0,
                'user_id' => $user->id, // Only for this user
                'valid_from' => $today->startOfDay(),
                'valid_until' => $today->copy()->endOfDay(),
                'is_active' => true,
            ]);

            // Send birthday email
            try {
                Mail::to($user->email)->send(new BirthdayWishMail($user, $coupon));
                $this->info("✅ Birthday coupon sent to {$user->name} ({$user->email}) - Code: {$couponCode} - Discount: {$discountPercentage}%");
            } catch (\Exception $e) {
                $this->error("Failed to send email to {$user->email}: " . $e->getMessage());
            }
        }

        $this->info('Birthday coupons generation completed!');
        return 0;
    }
}

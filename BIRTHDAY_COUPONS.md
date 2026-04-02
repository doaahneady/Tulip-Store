# Birthday Coupons System

## Overview
The birthday coupon system automatically generates personalized discount coupons for users on their birthday and sends them a beautiful email notification.

## Features
- Automatic coupon generation at 12:01 AM on user's birthday
- Random discount between 5% and 10%
- Coupon valid for 24 hours (birthday day only)
- Single-use coupon (can be used once)
- User-specific (only the birthday user can use it)
- Beautiful Arabic email with coupon code and instructions

## Setup

### 1. Database Migration
The system requires the `date_of_birth` column in the `users` table. Run migrations:
```bash
php artisan migrate
```

### 2. Schedule Configuration
The command is already scheduled in `app/Console/Kernel.php` to run daily at 12:01 AM:
```php
$schedule->command('coupons:generate-birthday')->dailyAt('00:01');
```

### 3. Laravel Scheduler
Make sure Laravel's scheduler is running. Add this to your cron:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Manual Testing
To manually test the birthday coupon generation:
```bash
php artisan coupons:generate-birthday
```

## How It Works

1. **Daily Check**: Every day at 12:01 AM, the system checks for users whose birthday is today
2. **Coupon Generation**: For each birthday user:
   - Generates a unique coupon code (format: `BIRTHDAY` + 6 random characters)
   - Sets random discount between 5% and 10%
   - Sets validity from 12:00 AM to 11:59 PM of birthday
   - Restricts usage to 1 time and only for that specific user
3. **Email Notification**: Sends a beautiful Arabic email with:
   - Birthday wishes from Tulip team
   - Coupon code prominently displayed
   - Discount percentage
   - Step-by-step instructions on how to use the coupon
   - Link to the website
   - Validity information

## Email Template
The email includes:
- Animated birthday header with emojis
- Personalized greeting
- Highlighted coupon code
- Discount badge
- Step-by-step usage instructions
- Call-to-action button to shop
- Validity notice

## Database Structure

### Users Table
- `birth_date` or `date_of_birth`: User's date of birth (DATE)

### Discount Coupons Table
- `code`: Unique coupon code
- `type`: 'percentage' or 'fixed'
- `value`: Discount value (5-10 for birthday coupons)
- `user_id`: Restricts coupon to specific user
- `usage_limit`: Maximum uses (1 for birthday coupons)
- `usage_count`: Current usage count
- `valid_from`: Start of validity period
- `valid_until`: End of validity period
- `is_active`: Active status

## Files Created/Modified

### New Files
- `app/Console/Commands/GenerateBirthdayCoupons.php` - Command to generate birthday coupons
- `app/Mail/BirthdayWishMail.php` - Birthday email template
- `database/migrations/*_add_date_of_birth_to_users_table.php`
- `database/migrations/*_add_user_id_to_discount_coupons_table.php`
- `database/migrations/*_add_birthday_coupon_fields_to_discount_coupons_table.php`

### Modified Files
- `app/Console/Kernel.php` - Added birthday coupon schedule
- `app/Models/User.php` - Added date_of_birth to fillable
- `app/Models/DiscountCoupon.php` - Updated for new fields and user-specific validation
- `app/Mail/OrderDeliveredMail.php` - Updated domain to https://tulip-os.com

## Notes
- Users must have a valid email address to receive birthday coupons
- Users must have their birth_date set in the database
- The system prevents duplicate coupons for the same user on the same day
- Failed email sends are logged but don't stop the process
- The coupon expires automatically at the end of the birthday (11:59 PM)

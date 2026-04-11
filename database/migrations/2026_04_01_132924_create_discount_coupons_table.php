<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('discount_coupons')) {
            Schema::create('discount_coupons', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->decimal('discount_percentage', 5, 2);
                $table->text('purpose')->nullable();
                $table->integer('max_uses')->nullable();
                $table->integer('used_count')->default(0);
                $table->timestamp('expires_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->foreignId('created_by')->nullable()->constrained('employees')->onDelete('set null');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('coupon_usage')) {
            Schema::create('coupon_usage', function (Blueprint $table) {
                $table->id();
                $table->foreignId('coupon_id')->constrained('discount_coupons')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
                $table->decimal('discount_amount', 10, 2);
                $table->decimal('order_total', 10, 2);
                $table->timestamp('used_at');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usage');
        Schema::dropIfExists('discount_coupons');
    }
};

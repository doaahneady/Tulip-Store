<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('discount_coupons', function (Blueprint $table) {
            if (!Schema::hasColumn('discount_coupons', 'type')) {
                $table->string('type')->default('percentage')->after('code'); // percentage or fixed
            }
            if (!Schema::hasColumn('discount_coupons', 'value')) {
                $table->decimal('value', 10, 2)->default(0)->after('type');
            }
            if (!Schema::hasColumn('discount_coupons', 'min_order_amount')) {
                $table->decimal('min_order_amount', 10, 2)->default(0)->after('value');
            }
            if (!Schema::hasColumn('discount_coupons', 'max_discount_amount')) {
                $table->decimal('max_discount_amount', 10, 2)->nullable()->after('min_order_amount');
            }
            if (!Schema::hasColumn('discount_coupons', 'usage_limit')) {
                $table->integer('usage_limit')->nullable()->after('max_discount_amount');
            }
            if (!Schema::hasColumn('discount_coupons', 'usage_count')) {
                $table->integer('usage_count')->default(0)->after('usage_limit');
            }
            if (!Schema::hasColumn('discount_coupons', 'valid_from')) {
                $table->timestamp('valid_from')->nullable()->after('usage_count');
            }
            if (!Schema::hasColumn('discount_coupons', 'valid_until')) {
                $table->timestamp('valid_until')->nullable()->after('valid_from');
            }
        });
    }

    public function down(): void
    {
        Schema::table('discount_coupons', function (Blueprint $table) {
            $columns = ['type', 'value', 'min_order_amount', 'max_discount_amount', 'usage_limit', 'usage_count', 'valid_from', 'valid_until'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('discount_coupons', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

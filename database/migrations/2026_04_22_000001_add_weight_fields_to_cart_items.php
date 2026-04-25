<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            if (! Schema::hasColumn('cart_items', 'is_weight_based')) {
                $table->boolean('is_weight_based')->default(false)->after('quantity');
            }
            if (! Schema::hasColumn('cart_items', 'weight_grams')) {
                $table->decimal('weight_grams', 10, 2)->nullable()->after('is_weight_based');
            }
            if (! Schema::hasColumn('cart_items', 'price_per_unit')) {
                $table->decimal('price_per_unit', 10, 2)->nullable()->after('weight_grams');
            }
            if (! Schema::hasColumn('cart_items', 'amount_paid')) {
                $table->decimal('amount_paid', 10, 2)->nullable()->after('price_per_unit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $columns = ['is_weight_based', 'weight_grams', 'price_per_unit', 'amount_paid'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('cart_items', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

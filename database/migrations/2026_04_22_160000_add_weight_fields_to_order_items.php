<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Add weight-based fields to track weight-based purchases
            $table->boolean('is_weight_based')->default(false)->after('quantity');
            $table->decimal('weight_grams', 10, 2)->nullable()->after('is_weight_based');
            $table->decimal('price_per_unit', 10, 2)->nullable()->after('weight_grams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['is_weight_based', 'weight_grams', 'price_per_unit']);
        });
    }
};

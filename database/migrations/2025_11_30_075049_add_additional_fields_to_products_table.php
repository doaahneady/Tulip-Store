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
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->unique()->nullable()->after('slug');
            $table->decimal('cost_price', 10, 2)->nullable()->after('discount_price');
            $table->string('meta_title')->nullable()->after('details');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->decimal('weight', 8, 2)->nullable()->after('meta_description'); // in kg
            $table->string('dimensions')->nullable()->after('weight'); // LxWxH in cm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sku', 'cost_price', 'meta_title', 'meta_description', 'weight', 'dimensions']);
        });
    }
};

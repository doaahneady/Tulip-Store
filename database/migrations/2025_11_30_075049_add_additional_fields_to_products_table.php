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
            if (! Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->unique()->nullable()->after('slug');
            }
            if (! Schema::hasColumn('products', 'cost_price')) {
                $table->decimal('cost_price', 10, 2)->nullable()->after('discount_price');
            }
            if (! Schema::hasColumn('products', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('details');
            }
            if (! Schema::hasColumn('products', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (! Schema::hasColumn('products', 'weight')) {
                $table->decimal('weight', 8, 2)->nullable()->after('meta_description');
            }
            if (! Schema::hasColumn('products', 'dimensions')) {
                $table->string('dimensions')->nullable()->after('weight');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'sku')) {
                $table->dropColumn('sku');
            }
            if (Schema::hasColumn('products', 'cost_price')) {
                $table->dropColumn('cost_price');
            }
            if (Schema::hasColumn('products', 'meta_title')) {
                $table->dropColumn('meta_title');
            }
            if (Schema::hasColumn('products', 'meta_description')) {
                $table->dropColumn('meta_description');
            }
            if (Schema::hasColumn('products', 'weight')) {
                $table->dropColumn('weight');
            }
            if (Schema::hasColumn('products', 'dimensions')) {
                $table->dropColumn('dimensions');
            }
        });
    }
};

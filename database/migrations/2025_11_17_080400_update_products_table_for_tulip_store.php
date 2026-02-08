<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'details')) {
                $table->text('details')->nullable()->after('description');
            }

            if (! Schema::hasColumn('products', 'discount_price')) {
                $table->decimal('discount_price', 10, 2)->nullable()->after('price');
            }

            if (! Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(0)->after('discount_price');
            }

            if (! Schema::hasColumn('products', 'images')) {
                $table->text('images')->nullable()->after('image');
            }

            if (! Schema::hasColumn('products', 'rating')) {
                $table->integer('rating')->default(0)->after('images');
            }

            if (! Schema::hasColumn('products', 'reviews_count')) {
                $table->integer('reviews_count')->default(0)->after('rating');
            }

            if (! Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('reviews_count');
            }

            if (! Schema::hasColumn('products', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_featured');
            }

            // Indexes for performance if not already present
            if (! Schema::hasColumn('products', 'category_id')) {
                // category_id should already exist from earlier migration, but guard just in case
                $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            }

            $table->index('category_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'is_active')) {
                $table->dropIndex(['is_active']);
                $table->dropColumn('is_active');
            }

            if (Schema::hasColumn('products', 'is_featured')) {
                $table->dropColumn('is_featured');
            }

            if (Schema::hasColumn('products', 'reviews_count')) {
                $table->dropColumn('reviews_count');
            }

            if (Schema::hasColumn('products', 'rating')) {
                $table->dropColumn('rating');
            }

            if (Schema::hasColumn('products', 'images')) {
                $table->dropColumn('images');
            }

            if (Schema::hasColumn('products', 'stock')) {
                $table->dropColumn('stock');
            }

            if (Schema::hasColumn('products', 'discount_price')) {
                $table->dropColumn('discount_price');
            }

            if (Schema::hasColumn('products', 'details')) {
                $table->dropColumn('details');
            }
        });
    }
};

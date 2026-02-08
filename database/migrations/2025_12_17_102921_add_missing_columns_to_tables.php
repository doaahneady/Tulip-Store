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
        // Add missing columns to orders table
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (! Schema::hasColumn('orders', 'total_amount')) {
                    $table->decimal('total_amount', 10, 2)->nullable()->after('total');
                }
            });

            // Copy data from 'total' to 'total_amount' if both exist
            if (Schema::hasColumn('orders', 'total') && Schema::hasColumn('orders', 'total_amount')) {
                DB::statement('UPDATE orders SET total_amount = total WHERE total_amount IS NULL');
            }
        }

        // Add missing columns to products table
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (! Schema::hasColumn('products', 'stock_quantity')) {
                    $table->integer('stock_quantity')->default(0)->after('stock');
                }
            });

            // Copy data from 'stock' to 'stock_quantity' if both exist
            if (Schema::hasColumn('products', 'stock') && Schema::hasColumn('products', 'stock_quantity')) {
                DB::statement('UPDATE products SET stock_quantity = stock WHERE stock_quantity = 0');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'total_amount')) {
                    $table->dropColumn('total_amount');
                }
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'stock_quantity')) {
                    $table->dropColumn('stock_quantity');
                }
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // Add product_type to distinguish between regular products and mart products
            if (! Schema::hasColumn('cart_items', 'product_type')) {
                $table->string('product_type')->default('regular')->after('product_id');
            }
            
            // Add mart product details (for virtual mart products)
            if (! Schema::hasColumn('cart_items', 'mart_product_name')) {
                $table->string('mart_product_name')->nullable()->after('product_type');
            }
            
            if (! Schema::hasColumn('cart_items', 'mart_product_image')) {
                $table->string('mart_product_image')->nullable()->after('mart_product_name');
            }
            
            if (! Schema::hasColumn('cart_items', 'mart_product_unit')) {
                $table->string('mart_product_unit')->nullable()->after('mart_product_image');
            }
            
            if (! Schema::hasColumn('cart_items', 'mart_product_emoji')) {
                $table->string('mart_product_emoji')->nullable()->after('mart_product_unit');
            }
        });

        // We need to drop the unique constraint to allow multiple entries for weight-based products
        // But first we need to handle the foreign key that depends on it
        // Let's use raw SQL to drop the constraint properly
        try {
            \DB::statement('ALTER TABLE cart_items DROP INDEX cart_items_cart_id_product_id_unique');
        } catch (\Exception $e) {
            // If it fails, it might already be dropped or not exist
            \Log::info('Could not drop unique index: ' . $e->getMessage());
        }
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $columns = ['product_type', 'mart_product_name', 'mart_product_image', 'mart_product_unit', 'mart_product_emoji'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('cart_items', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        // Re-add the unique constraint only if there are no duplicate entries
        $duplicates = \DB::table('cart_items')
            ->select('cart_id', 'product_id', \DB::raw('COUNT(*) as count'))
            ->groupBy('cart_id', 'product_id')
            ->having('count', '>', 1)
            ->count();

        if ($duplicates === 0) {
            try {
                \DB::statement('ALTER TABLE cart_items ADD UNIQUE INDEX cart_items_cart_id_product_id_unique (cart_id, product_id)');
            } catch (\Exception $e) {
                \Log::info('Could not re-add unique index: ' . $e->getMessage());
            }
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('products') || ! Schema::hasColumn('products', 'market')) {
            return;
        }

        if (Schema::hasColumn('products', 'store_id')) {
            DB::table('products')->where('store_id', 1)->update(['market' => 'mart']);
        }

        if (Schema::hasTable('product_attributes') && Schema::hasColumn('product_attributes', 'product_id') && Schema::hasColumn('product_attributes', 'name')) {
            $martProductIds = DB::table('product_attributes')
                ->whereIn('name', ['unit', 'origin'])
                ->pluck('product_id')
                ->unique()
                ->values()
                ->all();

            if (count($martProductIds) > 0) {
                DB::table('products')->whereIn('id', $martProductIds)->update(['market' => 'mart']);
            }
        }

        if (! Schema::hasTable('categories') || ! Schema::hasColumn('categories', 'market') || ! Schema::hasColumn('products', 'category_id')) {
            return;
        }

        $martCategoryIds = DB::table('products')
            ->where('market', 'mart')
            ->whereNotNull('category_id')
            ->pluck('category_id')
            ->unique()
            ->values()
            ->all();

        if (count($martCategoryIds) > 0) {
            DB::table('categories')->whereIn('id', $martCategoryIds)->update(['market' => 'mart']);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('products') || ! Schema::hasColumn('products', 'market')) {
            return;
        }

        DB::table('products')->where('market', 'mart')->update(['market' => 'store']);

        if (! Schema::hasTable('categories') || ! Schema::hasColumn('categories', 'market')) {
            return;
        }

        DB::table('categories')->where('market', 'mart')->update(['market' => 'store']);
    }
};


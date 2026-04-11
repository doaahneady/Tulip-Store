<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        if (! Schema::hasColumn('products', 'subcategory_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('subcategory_id')
                    ->nullable()
                    ->after('category_id')
                    ->constrained('subcategories')
                    ->nullOnDelete();
                $table->index('subcategory_id');
            });
        }

        if (! Schema::hasTable('subcategories') || ! Schema::hasTable('categories')) {
            return;
        }

        if (! Schema::hasColumn('categories', 'market') || ! Schema::hasColumn('products', 'market')) {
            return;
        }

        if (! Schema::hasColumn('products', 'category_id')) {
            return;
        }

        DB::transaction(function () {
            $martCategoryIds = DB::table('categories')
                ->where('market', 'mart')
                ->pluck('id')
                ->values()
                ->all();

            if (count($martCategoryIds) === 0) {
                return;
            }

            $existing = DB::table('subcategories')
                ->whereIn('category_id', $martCategoryIds)
                ->get(['id', 'category_id', 'slug']);

            $defaultByCategory = [];
            foreach ($existing as $row) {
                if ((string) $row->slug === 'general') {
                    $defaultByCategory[(int) $row->category_id] = (int) $row->id;
                }
            }

            $now = now();
            foreach ($martCategoryIds as $catId) {
                $catId = (int) $catId;
                if (isset($defaultByCategory[$catId])) {
                    continue;
                }
                $id = DB::table('subcategories')->insertGetId([
                    'category_id' => $catId,
                    'name' => 'عام',
                    'slug' => 'general',
                    'display_order' => 0,
                    'is_active' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $defaultByCategory[$catId] = (int) $id;
            }

            foreach ($defaultByCategory as $catId => $subId) {
                DB::table('products')
                    ->where('market', 'mart')
                    ->whereNull('subcategory_id')
                    ->where('category_id', $catId)
                    ->update(['subcategory_id' => $subId]);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        if (Schema::hasColumn('products', 'subcategory_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['subcategory_id']);
                $table->dropIndex(['subcategory_id']);
                $table->dropColumn('subcategory_id');
            });
        }
    }
};


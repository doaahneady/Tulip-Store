<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('categories') || ! Schema::hasTable('products')) {
            return;
        }

        if (! Schema::hasColumn('categories', 'market') || ! Schema::hasColumn('products', 'market')) {
            return;
        }

        $martSlugs = ['fruits', 'vegetables', 'khdroaat', 'khodraat', 'mart-fruits', 'mart-vegetables'];
        $martNames = ['فواكه', 'الخضروات', 'خضروات', 'الفواكه'];

        $martCategoryIds = DB::table('categories')
            ->whereIn('slug', $martSlugs)
            ->orWhereIn('name', $martNames)
            ->pluck('id')
            ->unique()
            ->values()
            ->all();

        if (count($martCategoryIds) === 0) {
            return;
        }

        DB::table('categories')->whereIn('id', $martCategoryIds)->update(['market' => 'mart']);
        DB::table('products')->whereIn('category_id', $martCategoryIds)->update(['market' => 'mart']);
    }

    public function down(): void
    {
        if (! Schema::hasTable('categories') || ! Schema::hasTable('products')) {
            return;
        }

        if (! Schema::hasColumn('categories', 'market') || ! Schema::hasColumn('products', 'market')) {
            return;
        }

        $martSlugs = ['fruits', 'vegetables', 'khdroaat', 'khodraat', 'mart-fruits', 'mart-vegetables'];
        $martNames = ['فواكه', 'الخضروات', 'خضروات', 'الفواكه'];

        $martCategoryIds = DB::table('categories')
            ->whereIn('slug', $martSlugs)
            ->orWhereIn('name', $martNames)
            ->pluck('id')
            ->unique()
            ->values()
            ->all();

        if (count($martCategoryIds) === 0) {
            return;
        }

        DB::table('categories')->whereIn('id', $martCategoryIds)->update(['market' => 'store']);
        DB::table('products')->whereIn('category_id', $martCategoryIds)->update(['market' => 'store']);
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('categories') && ! Schema::hasColumn('categories', 'market')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('market', 20)->default('store')->after('is_active');
                $table->index('market');
            });

            DB::table('categories')->whereNull('market')->update(['market' => 'store']);
        }

        if (Schema::hasTable('products') && ! Schema::hasColumn('products', 'market')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('market', 20)->default('store')->after('status');
                $table->index('market');
            });

            DB::table('products')->whereNull('market')->update(['market' => 'store']);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'market')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex(['market']);
                $table->dropColumn('market');
            });
        }

        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'market')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropIndex(['market']);
                $table->dropColumn('market');
            });
        }
    }
};


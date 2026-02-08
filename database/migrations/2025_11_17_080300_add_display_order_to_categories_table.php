<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (! Schema::hasColumn('categories', 'display_order')) {
                $table->integer('display_order')->default(0)->after('image');
            }

            if (! Schema::hasColumn('categories', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('display_order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'is_active')) {
                $table->dropColumn('is_active');
            }

            if (Schema::hasColumn('categories', 'display_order')) {
                $table->dropColumn('display_order');
            }
        });
    }
};

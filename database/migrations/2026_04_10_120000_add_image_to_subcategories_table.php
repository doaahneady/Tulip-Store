<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subcategories', function (Blueprint $table) {
            if (!Schema::hasColumn('subcategories', 'image')) {
                $table->string('image')->nullable()->after('slug');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subcategories', function (Blueprint $table) {
            if (Schema::hasColumn('subcategories', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};

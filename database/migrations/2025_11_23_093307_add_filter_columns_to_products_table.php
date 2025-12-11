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
            if (!Schema::hasColumn('products', 'color')) {
                $table->string('color')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'size')) {
                $table->string('size')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'material')) {
                $table->string('material')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'brand')) {
                $table->string('brand')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'age_range')) {
                $table->integer('age_range')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'author')) {
                $table->string('author')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'genre')) {
                $table->string('genre')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'pages')) {
                $table->integer('pages')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'condition')) {
                $table->string('condition')->default('new')->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'color',
                'size',
                'material',
                'brand',
                'age_range',
                'author',
                'genre',
                'pages',
                'condition',
                'stock'
            ]);
        });
    }
};

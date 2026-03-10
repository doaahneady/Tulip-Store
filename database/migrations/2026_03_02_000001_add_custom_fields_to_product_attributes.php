<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_attributes')) {
            return;
        }

        Schema::table('product_attributes', function (Blueprint $table) {
            if (! Schema::hasColumn('product_attributes', 'type')) {
                $table->string('type')->nullable()->after('name');
            }
            if (! Schema::hasColumn('product_attributes', 'options')) {
                $table->json('options')->nullable()->after('value');
            }
            if (! Schema::hasColumn('product_attributes', 'is_custom')) {
                $table->boolean('is_custom')->default(false)->after('options');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('product_attributes')) {
            return;
        }

        Schema::table('product_attributes', function (Blueprint $table) {
            $cols = ['type', 'options', 'is_custom'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('product_attributes', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};


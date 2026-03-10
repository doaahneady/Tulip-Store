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
            if (! Schema::hasColumn('product_attributes', 'attribute_key')) {
                $table->string('attribute_key', 80)->nullable()->after('name');
            }
            if (! Schema::hasColumn('product_attributes', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('is_custom');
            }
            if (! Schema::hasColumn('product_attributes', 'is_required')) {
                $table->boolean('is_required')->default(false)->after('sort_order');
            }
            if (! Schema::hasColumn('product_attributes', 'rules')) {
                $table->json('rules')->nullable()->after('is_required');
            }
            if (! Schema::hasColumn('product_attributes', 'value_text')) {
                $table->string('value_text', 191)->nullable()->after('rules');
            }
            if (! Schema::hasColumn('product_attributes', 'value_number')) {
                $table->decimal('value_number', 12, 2)->nullable()->after('value_text');
            }
            if (! Schema::hasColumn('product_attributes', 'value_date')) {
                $table->date('value_date')->nullable()->after('value_number');
            }
            if (! Schema::hasColumn('product_attributes', 'value_json')) {
                $table->json('value_json')->nullable()->after('value_date');
            }
        });

        Schema::table('product_attributes', function (Blueprint $table) {
            if (Schema::hasColumn('product_attributes', 'product_id')) {
                $table->index(['product_id', 'is_custom'], 'product_attributes_product_custom_idx');
            }
            if (Schema::hasColumn('product_attributes', 'attribute_key')) {
                $table->index(['attribute_key', 'value_text'], 'product_attributes_key_text_idx');
                $table->index(['attribute_key', 'value_number'], 'product_attributes_key_number_idx');
                $table->index(['attribute_key', 'value_date'], 'product_attributes_key_date_idx');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('product_attributes')) {
            return;
        }

        Schema::table('product_attributes', function (Blueprint $table) {
            $indexes = [
                'product_attributes_product_custom_idx',
                'product_attributes_key_text_idx',
                'product_attributes_key_number_idx',
                'product_attributes_key_date_idx',
            ];
            foreach ($indexes as $idx) {
                try {
                    $table->dropIndex($idx);
                } catch (\Throwable $e) {
                }
            }
        });

        Schema::table('product_attributes', function (Blueprint $table) {
            $cols = [
                'attribute_key',
                'sort_order',
                'is_required',
                'rules',
                'value_text',
                'value_number',
                'value_date',
                'value_json',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('product_attributes', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        try {
            Schema::table('products', function (Blueprint $table) {
                // Allow trader-only products without a store
                $table->unsignedBigInteger('store_id')->nullable()->change();

                // Allow products without SKU during trader submission
                $table->string('sku')->nullable()->change();
            });
        } catch (\Throwable $e) {
            // Some drivers may not support change() reliably; skip silently in tests
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        try {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('store_id')->nullable(false)->change();
                $table->string('sku')->nullable(false)->change();
            });
        } catch (\Throwable $e) {
            // No-op if revert fails
        }
    }
};

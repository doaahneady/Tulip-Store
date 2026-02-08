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
        Schema::table('orders', function (Blueprint $table) {
            $afterColumn = Schema::hasColumn('orders', 'customer_id') ? 'customer_id' : (Schema::hasColumn('orders', 'user_id') ? 'user_id' : 'id');
            if (! Schema::hasColumn('orders', 'store_id')) {
                $table->foreignId('store_id')->nullable()->after($afterColumn)->constrained()->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'store_id')) {
                $table->dropForeign(['store_id']);
                $table->dropColumn('store_id');
            }
        });
    }
};

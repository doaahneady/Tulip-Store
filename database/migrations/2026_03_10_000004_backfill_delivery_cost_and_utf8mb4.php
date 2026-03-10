<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'delivery_cost') && Schema::hasColumn('orders', 'shipping_cost')) {
            DB::table('orders')
                ->where(function ($q) {
                    $q->whereNull('delivery_cost')->orWhere('delivery_cost', 0);
                })
                ->whereNotNull('shipping_cost')
                ->where('shipping_cost', '>', 0)
                ->update(['delivery_cost' => DB::raw('shipping_cost')]);

            DB::table('orders')
                ->where(function ($q) {
                    $q->whereNull('shipping_cost')->orWhere('shipping_cost', 0);
                })
                ->whereNotNull('delivery_cost')
                ->where('delivery_cost', '>', 0)
                ->update(['shipping_cost' => DB::raw('delivery_cost')]);
        }

        $driver = DB::connection()->getDriverName();
        if ($driver !== 'mysql') {
            return;
        }

        foreach (['products', 'order_items', 'orders'] as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            try {
                DB::statement("ALTER TABLE `{$table}` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            } catch (\Throwable $e) {
            }
        }
    }

    public function down(): void
    {
    }
};


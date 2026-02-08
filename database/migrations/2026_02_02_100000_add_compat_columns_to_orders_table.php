<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'user_id')) {
                $after = Schema::hasColumn('orders', 'customer_id') ? 'customer_id' : 'id';
                $table->unsignedBigInteger('user_id')->nullable()->after($after);
            }

            if (! Schema::hasColumn('orders', 'total')) {
                $after = Schema::hasColumn('orders', 'subtotal') ? 'subtotal' : 'id';
                $table->decimal('total', 10, 2)->nullable()->after($after);
            }
        });

        if (Schema::hasColumn('orders', 'customer_id') && Schema::hasColumn('orders', 'user_id')) {
            DB::statement('UPDATE orders SET user_id = customer_id WHERE user_id IS NULL');
        }

        if (Schema::hasColumn('orders', 'total_amount') && Schema::hasColumn('orders', 'total')) {
            DB::statement('UPDATE orders SET total = total_amount WHERE total IS NULL');
            DB::statement('UPDATE orders SET total_amount = total WHERE total_amount IS NULL');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'user_id')) {
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('orders', 'total')) {
                $table->dropColumn('total');
            }
        });
    }
};

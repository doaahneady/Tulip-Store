<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'idempotency_key')) {
                $after = Schema::hasColumn('orders', 'payment_reference') ? 'payment_reference' : 'payment_method';
                $table->string('idempotency_key', 80)->nullable()->after($after);
            }
        });

        if (Schema::hasColumn('orders', 'customer_id') && Schema::hasColumn('orders', 'idempotency_key')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unique(['customer_id', 'idempotency_key'], 'orders_customer_id_idempotency_key_unique');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'idempotency_key')) {
                $table->dropUnique('orders_customer_id_idempotency_key_unique');
                $table->dropColumn('idempotency_key');
            }
        });
    }
};


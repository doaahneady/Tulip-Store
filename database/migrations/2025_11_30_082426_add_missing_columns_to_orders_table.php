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
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (! Schema::hasColumn('orders', 'payment_method')) {
                    $table->string('payment_method')->default('cash')->after('status');
                }
                if (! Schema::hasColumn('orders', 'payment_status')) {
                    $table->string('payment_status')->default('pending')->after('payment_method');
                }
                if (! Schema::hasColumn('orders', 'recipient_name')) {
                    $table->string('recipient_name')->nullable()->after('user_id');
                }
                if (! Schema::hasColumn('orders', 'phone')) {
                    $table->string('phone')->nullable()->after('recipient_name');
                }
                if (! Schema::hasColumn('orders', 'village')) {
                    $table->string('village')->nullable()->after('phone');
                }
                if (! Schema::hasColumn('orders', 'address_note')) {
                    $table->text('address_note')->nullable()->after('village');
                }
                if (! Schema::hasColumn('orders', 'latitude')) {
                    $table->decimal('latitude', 10, 7)->nullable()->after('address_note');
                }
                if (! Schema::hasColumn('orders', 'longitude')) {
                    $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
                }
                if (! Schema::hasColumn('orders', 'delivery_method')) {
                    $table->string('delivery_method')->nullable()->after('longitude');
                }
                if (! Schema::hasColumn('orders', 'delivery_cost')) {
                    $table->decimal('delivery_cost', 10, 2)->default(0)->after('subtotal');
                }
                if (! Schema::hasColumn('orders', 'service_fee')) {
                    $table->decimal('service_fee', 10, 2)->default(0)->after('delivery_cost');
                }
                if (! Schema::hasColumn('orders', 'payment_receipt')) {
                    $table->string('payment_receipt')->nullable()->after('payment_status');
                }
                if (! Schema::hasColumn('orders', 'admin_notes')) {
                    $table->text('admin_notes')->nullable()->after('payment_receipt');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                foreach ([
                    'payment_method', 'payment_status', 'recipient_name', 'phone',
                    'village', 'address_note', 'latitude', 'longitude', 'delivery_method',
                    'delivery_cost', 'service_fee', 'payment_receipt', 'admin_notes',
                ] as $col) {
                    if (Schema::hasColumn('orders', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};

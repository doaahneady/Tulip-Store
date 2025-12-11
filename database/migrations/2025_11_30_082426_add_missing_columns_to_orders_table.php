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
            $table->string('payment_method')->default('cash')->after('status');
            $table->string('payment_status')->default('pending')->after('payment_method');
            $table->string('recipient_name')->nullable()->after('user_id');
            $table->string('phone')->nullable()->after('recipient_name');
            $table->string('village')->nullable()->after('phone');
            $table->text('address_note')->nullable()->after('village');
            $table->decimal('latitude', 10, 7)->nullable()->after('address_note');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('delivery_method')->nullable()->after('longitude');
            $table->decimal('delivery_cost', 10, 2)->default(0)->after('subtotal');
            $table->decimal('service_fee', 10, 2)->default(0)->after('delivery_cost');
            $table->string('payment_receipt')->nullable()->after('payment_status');
            $table->text('admin_notes')->nullable()->after('payment_receipt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method', 'payment_status', 'recipient_name', 'phone', 
                'village', 'address_note', 'latitude', 'longitude', 'delivery_method',
                'delivery_cost', 'service_fee', 'payment_receipt', 'admin_notes'
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Driver assignment
            $table->foreignId('assigned_driver_id')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            $table->timestamp('assigned_at')->nullable()->after('assigned_driver_id');
            
            // Customer confirmation
            $table->string('confirmation_token')->nullable()->unique()->after('payment_status');
            $table->timestamp('confirmed_at')->nullable()->after('confirmation_token');
            $table->text('customer_signature')->nullable()->after('confirmed_at');
            
            // Additional tracking
            $table->foreignId('assigned_by')->nullable()->after('assigned_at')->constrained('users')->onDelete('set null');
            $table->text('delivery_notes')->nullable()->after('address_note');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['assigned_driver_id']);
            $table->dropForeign(['assigned_by']);
            $table->dropColumn([
                'assigned_driver_id',
                'assigned_at',
                'assigned_by',
                'confirmation_token',
                'confirmed_at',
                'customer_signature',
                'delivery_notes'
            ]);
        });
    }
};

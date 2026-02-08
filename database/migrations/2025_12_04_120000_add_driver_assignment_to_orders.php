<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $afterColumn = Schema::hasColumn('orders', 'customer_id') ? 'customer_id' : (Schema::hasColumn('orders', 'user_id') ? 'user_id' : 'id');

            // Driver assignment
            if (! Schema::hasColumn('orders', 'assigned_driver_id')) {
                $table->foreignId('assigned_driver_id')->nullable()->after($afterColumn)->constrained('users')->onDelete('set null');
            }
            if (! Schema::hasColumn('orders', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable()->after('assigned_driver_id');
            }

            // Customer confirmation
            if (! Schema::hasColumn('orders', 'confirmation_token')) {
                $table->string('confirmation_token')->nullable()->unique()->after('payment_status');
            }
            if (! Schema::hasColumn('orders', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('confirmation_token');
            }
            if (! Schema::hasColumn('orders', 'customer_signature')) {
                $table->text('customer_signature')->nullable()->after('confirmed_at');
            }

            // Additional tracking
            if (! Schema::hasColumn('orders', 'assigned_by')) {
                $table->foreignId('assigned_by')->nullable()->after('assigned_at')->constrained('users')->onDelete('set null');
            }
            if (! Schema::hasColumn('orders', 'delivery_notes')) {
                $table->text('delivery_notes')->nullable()->after('address_note');
            }
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
                'delivery_notes',
            ]);
        });
    }
};

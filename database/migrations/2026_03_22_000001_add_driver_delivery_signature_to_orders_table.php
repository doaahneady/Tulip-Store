<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'driver_delivery_signature')) {
                $table->text('driver_delivery_signature')->nullable()->after('customer_signature');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'driver_delivery_signature')) {
                $table->dropColumn('driver_delivery_signature');
            }
        });
    }
};

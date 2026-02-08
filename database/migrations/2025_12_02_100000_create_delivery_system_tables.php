<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Delivery Drivers Table
        if (! Schema::hasTable('delivery_drivers')) {
            Schema::create('delivery_drivers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('driver_name');
                $table->string('phone')->unique();
                $table->string('vehicle_type');
                $table->string('vehicle_plate');
                $table->string('license_number');
                $table->enum('status', ['available', 'busy', 'offline', 'on_break'])->default('offline');
                $table->decimal('current_latitude', 10, 7)->nullable();
                $table->decimal('current_longitude', 10, 7)->nullable();
                $table->timestamp('last_location_update')->nullable();
                $table->integer('total_deliveries')->default(0);
                $table->decimal('rating', 3, 2)->default(5.00);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Driver Location History
        if (! Schema::hasTable('driver_location_history')) {
            Schema::create('driver_location_history', function (Blueprint $table) {
                $table->id();
                $table->foreignId('driver_id')->constrained('delivery_drivers')->onDelete('cascade');
                $table->decimal('latitude', 10, 7);
                $table->decimal('longitude', 10, 7);
                $table->decimal('speed', 5, 2)->nullable();
                $table->decimal('accuracy', 8, 2)->nullable();
                $table->string('battery_level')->nullable();
                $table->timestamp('recorded_at');
                $table->timestamps();

                $table->index(['driver_id', 'recorded_at']);
            });
        }

        // Delivery Assignments
        if (! Schema::hasTable('delivery_assignments')) {
            Schema::create('delivery_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->foreignId('driver_id')->constrained('delivery_drivers')->onDelete('cascade');
                $table->enum('status', ['assigned', 'picked_up', 'in_transit', 'delivered', 'failed', 'cancelled'])->default('assigned');
                $table->timestamp('assigned_at');
                $table->timestamp('picked_up_at')->nullable();
                $table->timestamp('delivered_at')->nullable();
                $table->decimal('pickup_latitude', 10, 7)->nullable();
                $table->decimal('pickup_longitude', 10, 7)->nullable();
                $table->decimal('delivery_latitude', 10, 7)->nullable();
                $table->decimal('delivery_longitude', 10, 7)->nullable();
                $table->decimal('distance_km', 8, 2)->nullable();
                $table->integer('estimated_time_minutes')->nullable();
                $table->text('delivery_notes')->nullable();
                $table->string('customer_signature')->nullable();
                $table->text('failure_reason')->nullable();
                $table->timestamps();
            });
        }

        // Driver Performance Metrics
        if (! Schema::hasTable('driver_performance')) {
            Schema::create('driver_performance', function (Blueprint $table) {
                $table->id();
                $table->foreignId('driver_id')->constrained('delivery_drivers')->onDelete('cascade');
                $table->date('date');
                $table->integer('deliveries_completed')->default(0);
                $table->integer('deliveries_failed')->default(0);
                $table->decimal('total_distance_km', 10, 2)->default(0);
                $table->integer('total_time_minutes')->default(0);
                $table->decimal('average_rating', 3, 2)->default(5.00);
                $table->integer('on_time_deliveries')->default(0);
                $table->integer('late_deliveries')->default(0);
                $table->timestamps();

                $table->unique(['driver_id', 'date']);
            });
        }

        // Delivery Zones
        if (! Schema::hasTable('delivery_zones')) {
            Schema::create('delivery_zones', function (Blueprint $table) {
                $table->id();
                $table->string('zone_name');
                $table->text('zone_coordinates');
                $table->decimal('base_delivery_fee', 8, 2);
                $table->integer('estimated_time_minutes');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_zones');
        Schema::dropIfExists('driver_performance');
        Schema::dropIfExists('delivery_assignments');
        Schema::dropIfExists('driver_location_history');
        Schema::dropIfExists('delivery_drivers');
    }
};

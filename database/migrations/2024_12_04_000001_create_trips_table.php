<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            
            // Pickup details
            $table->string('pickup_address');
            $table->decimal('pickup_latitude', 10, 8)->nullable();
            $table->decimal('pickup_longitude', 11, 8)->nullable();
            
            // Delivery details
            $table->string('delivery_address');
            $table->decimal('delivery_latitude', 10, 8)->nullable();
            $table->decimal('delivery_longitude', 11, 8)->nullable();
            
            // Trip details
            $table->decimal('distance', 8, 2)->nullable(); // km
            $table->integer('estimated_time')->nullable(); // minutes
            $table->integer('actual_time')->nullable(); // minutes
            
            // Status
            $table->enum('status', ['pending', 'accepted', 'picked_up', 'in_transit', 'delivered', 'cancelled'])->default('pending');
            
            // Payment
            $table->decimal('payment_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['pending', 'paid'])->default('pending');
            
            // Timestamps
            $table->timestamp('assigned_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['driver_id', 'status']);
            $table->index(['order_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['assigned', 'picked_up', 'in_transit', 'delivered', 'failed', 'cancelled'])->default('assigned');
            $table->timestamp('assigned_at');
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->decimal('delivery_latitude', 10, 8)->nullable();
            $table->decimal('delivery_longitude', 11, 8)->nullable();
            $table->text('notes')->nullable();
            $table->string('customer_signature')->nullable();
            $table->timestamps();
            
            $table->index(['driver_id', 'status']);
            $table->index(['order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_assignments');
    }
};

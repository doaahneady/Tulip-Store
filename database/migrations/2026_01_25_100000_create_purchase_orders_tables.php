<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('purchase_orders')) {
            Schema::create('purchase_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_id')->nullable()->constrained()->onDelete('set null');
                $table->string('supplier_name')->nullable();
                $table->string('supplier_contact')->nullable();
                $table->string('status')->default('pending_approval');
                $table->date('expected_delivery_date')->nullable();
                $table->decimal('total_cost', 12, 2)->default(0);
                $table->foreignId('created_by')->nullable()->constrained('employees')->onDelete('set null');
                $table->foreignId('approved_by')->nullable()->constrained('employees')->onDelete('set null');
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('received_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['store_id', 'status']);
            });
        }

        if (! Schema::hasTable('purchase_order_items')) {
            Schema::create('purchase_order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('quantity');
                $table->integer('received_quantity')->default(0);
                $table->decimal('unit_cost', 12, 2)->default(0);
                $table->timestamps();

                $table->index(['purchase_order_id', 'product_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
    }
};

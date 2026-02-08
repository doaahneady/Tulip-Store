<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('traders')) {
            Schema::create('traders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('name');
                $table->string('company_name')->nullable();
                $table->string('contact_email')->nullable();
                $table->string('contact_phone')->nullable();
                $table->enum('status', ['pending', 'approved', 'suspended', 'rejected'])->default('pending');
                $table->decimal('commission_rate', 5, 2)->default(10.00);
                $table->json('payout_settings')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->index(['status']);
                $table->unique(['user_id']);
            });
        } else {
            Schema::table('traders', function (Blueprint $table) {
                if (! Schema::hasColumn('traders', 'payout_settings')) {
                    $table->json('payout_settings')->nullable();
                }
                if (! Schema::hasColumn('traders', 'commission_rate')) {
                    $table->decimal('commission_rate', 5, 2)->default(10.00);
                }
                if (! Schema::hasColumn('traders', 'status')) {
                    $table->enum('status', ['pending', 'approved', 'suspended', 'rejected'])->default('pending');
                }
            });
        }

        if (! Schema::hasTable('trader_products')) {
            Schema::create('trader_products', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('trader_id');
                $table->unsignedBigInteger('product_id');
                $table->decimal('price_override', 12, 2)->nullable();
                $table->enum('stock_managed_by', ['platform', 'trader'])->default('platform');
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();

                $table->foreign('trader_id')->references('id')->on('traders')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->unique(['trader_id', 'product_id']);
                $table->index(['status']);
            });
        }

        if (! Schema::hasTable('trader_orders')) {
            Schema::create('trader_orders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('trader_id');
                $table->unsignedBigInteger('order_id');
                $table->decimal('subtotal', 12, 2)->default(0);
                $table->decimal('commission_amount', 12, 2)->default(0);
                $table->decimal('net_amount', 12, 2)->default(0);
                $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
                $table->timestamps();

                $table->foreign('trader_id')->references('id')->on('traders')->onDelete('cascade');
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
                $table->unique(['trader_id', 'order_id']);
                $table->index(['status']);
                $table->index(['created_at']);
            });
        }

        if (! Schema::hasTable('trader_payouts')) {
            Schema::create('trader_payouts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('trader_id');
                $table->decimal('amount', 12, 2);
                $table->string('currency', 3)->default('USD');
                $table->enum('status', ['pending', 'approved', 'processing', 'completed', 'rejected'])->default('pending');
                $table->json('bank_details')->nullable();
                $table->unsignedBigInteger('processed_by')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->string('reference_number')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('trader_id')->references('id')->on('traders')->onDelete('cascade');
                $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
                $table->index(['status']);
                $table->index(['created_at']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('trader_payouts')) {
            Schema::dropIfExists('trader_payouts');
        }
        if (Schema::hasTable('trader_orders')) {
            Schema::dropIfExists('trader_orders');
        }
        if (Schema::hasTable('trader_products')) {
            Schema::dropIfExists('trader_products');
        }
        if (Schema::hasTable('traders')) {
            Schema::dropIfExists('traders');
        }
    }
};

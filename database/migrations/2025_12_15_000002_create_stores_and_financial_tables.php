<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Stores table
        if (! Schema::hasTable('stores')) {
            Schema::create('stores', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('logo')->nullable();
                $table->string('banner')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->enum('status', ['pending', 'approved', 'suspended', 'rejected'])->default('pending');
                $table->decimal('commission_rate', 5, 2)->default(10.00);
                $table->decimal('total_sales', 12, 2)->default(0);
                $table->decimal('total_commission', 12, 2)->default(0);
                $table->decimal('balance', 12, 2)->default(0);
                $table->boolean('is_featured')->default(false);
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::table('stores', function (Blueprint $table) {
                if (! Schema::hasColumn('stores', 'banner')) {
                    $table->string('banner')->nullable();
                }
                if (! Schema::hasColumn('stores', 'phone')) {
                    $table->string('phone')->nullable();
                }
                if (! Schema::hasColumn('stores', 'email')) {
                    $table->string('email')->nullable();
                }
                if (! Schema::hasColumn('stores', 'address')) {
                    $table->text('address')->nullable();
                }
                if (! Schema::hasColumn('stores', 'total_sales')) {
                    $table->decimal('total_sales', 12, 2)->default(0);
                }
                if (! Schema::hasColumn('stores', 'total_commission')) {
                    $table->decimal('total_commission', 12, 2)->default(0);
                }
                if (! Schema::hasColumn('stores', 'balance')) {
                    $table->decimal('balance', 12, 2)->default(0);
                }
                if (! Schema::hasColumn('stores', 'is_featured')) {
                    $table->boolean('is_featured')->default(false);
                }
                if (! Schema::hasColumn('stores', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        // Financial Transactions table
        if (! Schema::hasTable('financial_transactions')) {
            Schema::create('financial_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->enum('type', ['sale', 'commission', 'payout', 'refund', 'fee', 'adjustment']);
                $table->decimal('amount', 12, 2);
                $table->decimal('balance_before', 12, 2)->default(0);
                $table->decimal('balance_after', 12, 2)->default(0);
                $table->string('reference')->nullable();
                $table->text('description')->nullable();
                $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('completed');
                $table->timestamps();

                $table->index(['store_id', 'created_at']);
                $table->index(['type', 'created_at']);
            });
        } else {
            Schema::table('financial_transactions', function (Blueprint $table) {
                if (! Schema::hasColumn('financial_transactions', 'balance_before')) {
                    $table->decimal('balance_before', 12, 2)->default(0);
                }
                if (! Schema::hasColumn('financial_transactions', 'balance_after')) {
                    $table->decimal('balance_after', 12, 2)->default(0);
                }
                if (! Schema::hasColumn('financial_transactions', 'reference')) {
                    $table->string('reference')->nullable();
                }
            });
        }

        // Payouts table
        if (! Schema::hasTable('payouts')) {
            Schema::create('payouts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_id')->constrained()->onDelete('cascade');
                $table->decimal('amount', 12, 2);
                $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
                $table->string('payment_method')->nullable();
                $table->string('payment_reference')->nullable();
                $table->text('notes')->nullable();
                $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('payouts', function (Blueprint $table) {
                if (! Schema::hasColumn('payouts', 'payment_method')) {
                    $table->string('payment_method')->nullable();
                }
                if (! Schema::hasColumn('payouts', 'payment_reference')) {
                    $table->string('payment_reference')->nullable();
                }
            });
        }

        // Audit Logs table
        if (! Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->string('action');
                $table->string('model_type')->nullable();
                $table->unsignedBigInteger('model_id')->nullable();
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamps();

                $table->index(['model_type', 'model_id']);
                $table->index(['user_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('payouts');
        Schema::dropIfExists('financial_transactions');
        Schema::dropIfExists('stores');
    }
};

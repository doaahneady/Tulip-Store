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
        // Add missing columns for IT dashboard
        if (Schema::hasTable('system_services')) {
            Schema::table('system_services', function (Blueprint $table) {
                if (! Schema::hasColumn('system_services', 'response_time')) {
                    $table->integer('response_time')->default(0)->after('status'); // in milliseconds
                }
            });
        } else {
            // Create system_services table if it doesn't exist
            Schema::create('system_services', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type')->default('service');
                $table->enum('status', ['online', 'offline', 'degraded'])->default('online');
                $table->integer('response_time')->default(0); // in milliseconds
                $table->decimal('uptime_percentage', 5, 2)->default(99.9);
                $table->timestamp('last_check')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        // Add missing columns for Finance dashboard
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (! Schema::hasColumn('orders', 'tax_amount')) {
                    $table->decimal('tax_amount', 10, 2)->default(0)->after('total');
                }
                if (! Schema::hasColumn('orders', 'commission_amount')) {
                    $table->decimal('commission_amount', 10, 2)->default(0)->after('tax_amount');
                }
            });
        }

        // Create employees table if it doesn't exist (for HR dashboard)
        if (! Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('employee_id')->unique();
                $table->string('department');
                $table->string('position');
                $table->enum('status', ['active', 'inactive', 'on_leave', 'terminated'])->default('active');
                $table->date('hire_date');
                $table->decimal('salary', 10, 2);
                $table->string('manager_id')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        // Create system_alerts table if it doesn't exist
        if (! Schema::hasTable('system_alerts')) {
            Schema::create('system_alerts', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description');
                $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
                $table->enum('status', ['active', 'acknowledged', 'resolved'])->default('active');
                $table->string('source')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamp('acknowledged_at')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();
            });
        }

        // Create payouts table if it doesn't exist
        if (! Schema::hasTable('payouts')) {
            Schema::create('payouts', function (Blueprint $table) {
                $table->id();
                $table->string('payout_id')->unique();
                $table->foreignId('store_id')->constrained()->onDelete('cascade');
                $table->decimal('amount', 10, 2);
                $table->enum('status', ['pending', 'approved', 'processing', 'completed', 'failed', 'rejected'])->default('pending');
                $table->string('payment_method')->default('bank_transfer');
                $table->json('bank_details')->nullable();
                $table->text('notes')->nullable();
                $table->timestamp('requested_at');
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove added columns
        if (Schema::hasTable('system_services')) {
            Schema::table('system_services', function (Blueprint $table) {
                $table->dropColumn(['response_time']);
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn(['tax_amount', 'commission_amount']);
            });
        }

        // Drop created tables
        Schema::dropIfExists('employees');
        Schema::dropIfExists('system_alerts');
        Schema::dropIfExists('payouts');
    }
};

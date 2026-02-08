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
        // System Settings Table (if not exists)
        if (! Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('type')->default('string'); // string, boolean, integer, json
                $table->string('category')->default('general');
                $table->string('description')->nullable();
                $table->boolean('is_public')->default(false);
                $table->timestamps();

                $table->index(['category', 'is_public']);
            });
        }

        // Audit Logs Table (if not exists)
        if (! Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('user_type')->default('App\\Models\\Employee');
                $table->string('action');
                $table->string('model_type')->nullable();
                $table->unsignedBigInteger('model_id')->nullable();
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'action']);
                $table->index(['model_type', 'model_id']);
                $table->index('created_at');
            });
        }

        // Roles Table (if not exists)
        if (! Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('display_name');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Permissions Table (if not exists)
        if (! Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('display_name');
                $table->text('description')->nullable();
                $table->string('category')->default('general');
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index('category');
            });
        }

        // Role Permissions Table (if not exists)
        if (! Schema::hasTable('role_permissions')) {
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('permission_id');
                $table->timestamps();

                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
                $table->unique(['role_id', 'permission_id']);
            });
        }

        // User Roles Table (if not exists)
        if (! Schema::hasTable('user_roles')) {
            Schema::create('user_roles', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('assigned_by')->nullable();
                $table->timestamp('assigned_at');
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->foreign('assigned_by')->references('id')->on('employees')->onDelete('set null');
                $table->unique(['user_id', 'role_id']);
            });
        }

        // Financial Transactions Table (if not exists)
        if (! Schema::hasTable('financial_transactions')) {
            Schema::create('financial_transactions', function (Blueprint $table) {
                $table->id();
                $table->string('transaction_id')->unique();
                $table->unsignedBigInteger('order_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('store_id')->nullable();
                $table->enum('type', ['payment', 'refund', 'commission', 'payout', 'fee', 'adjustment']);
                $table->decimal('amount', 15, 2);
                $table->string('currency', 3)->default('USD');
                $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled']);
                $table->string('payment_method')->nullable();
                $table->string('gateway')->nullable();
                $table->string('gateway_transaction_id')->nullable();
                $table->text('description')->nullable();
                $table->json('metadata')->nullable();
                $table->unsignedBigInteger('processed_by')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('store_id')->references('id')->on('stores')->onDelete('set null');
                $table->foreign('processed_by')->references('id')->on('employees')->onDelete('set null');
                $table->index(['type', 'status']);
                $table->index('created_at');
            });
        }

        // Support Tickets Table (if not exists)
        if (! Schema::hasTable('support_tickets')) {
            Schema::create('support_tickets', function (Blueprint $table) {
                $table->id();
                $table->string('ticket_number')->unique();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('assigned_to')->nullable();
                $table->string('subject');
                $table->text('description');
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
                $table->enum('status', ['open', 'in_progress', 'waiting_customer', 'resolved', 'closed'])->default('open');
                $table->string('category')->nullable();
                $table->json('tags')->nullable();
                $table->timestamp('first_response_at')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('assigned_to')->references('id')->on('employees')->onDelete('set null');
                $table->index(['status', 'priority']);
            });
        }

        // Support Ticket Replies Table (if not exists)
        if (! Schema::hasTable('support_ticket_replies')) {
            Schema::create('support_ticket_replies', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ticket_id');
                $table->morphs('author'); // Can be user or employee
                $table->text('message');
                $table->json('attachments')->nullable();
                $table->boolean('is_internal')->default(false);
                $table->timestamps();

                $table->foreign('ticket_id')->references('id')->on('support_tickets')->onDelete('cascade');
            });
        }

        // Performance Metrics Table (if not exists)
        if (! Schema::hasTable('performance_metrics')) {
            Schema::create('performance_metrics', function (Blueprint $table) {
                $table->id();
                $table->string('metric_name');
                $table->string('metric_type'); // daily, weekly, monthly
                $table->date('metric_date');
                $table->decimal('value', 15, 4);
                $table->string('category')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->unique(['metric_name', 'metric_type', 'metric_date']);
                $table->index(['metric_name', 'metric_date']);
            });
        }

        // System Backups Table (if not exists)
        if (! Schema::hasTable('system_backups')) {
            Schema::create('system_backups', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type'); // full, incremental, database, files
                $table->string('status'); // pending, running, completed, failed
                $table->bigInteger('size_bytes')->nullable();
                $table->string('file_path')->nullable();
                $table->text('description')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->foreign('created_by')->references('id')->on('employees')->onDelete('set null');
                $table->index(['type', 'status']);
            });
        }

        // Inventory Movements Table (if not exists)
        if (! Schema::hasTable('inventory_movements')) {
            Schema::create('inventory_movements', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->enum('type', ['in', 'out', 'adjustment', 'transfer']);
                $table->integer('quantity');
                $table->integer('previous_stock');
                $table->integer('new_stock');
                $table->string('reason')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('order_id')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
                $table->foreign('created_by')->references('id')->on('employees')->onDelete('set null');
                $table->index(['product_id', 'type']);
            });
        }

        // Driver Locations Table (if not exists)
        if (! Schema::hasTable('driver_locations')) {
            Schema::create('driver_locations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('driver_id');
                $table->decimal('latitude', 10, 7);
                $table->decimal('longitude', 10, 7);
                $table->decimal('accuracy', 8, 2)->nullable();
                $table->decimal('speed', 8, 2)->nullable();
                $table->decimal('heading', 8, 2)->nullable();
                $table->enum('status', ['available', 'busy', 'offline'])->default('available');
                $table->timestamp('recorded_at');
                $table->timestamps();

                $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
                $table->index(['driver_id', 'recorded_at']);
                $table->index(['latitude', 'longitude']);
            });
        }

        // Delivery Assignments Table (if not exists)
        if (! Schema::hasTable('delivery_assignments')) {
            Schema::create('delivery_assignments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('driver_id');
                $table->unsignedBigInteger('assigned_by');
                $table->enum('status', ['assigned', 'accepted', 'picked_up', 'in_transit', 'delivered', 'failed', 'cancelled']);
                $table->timestamp('assigned_at');
                $table->timestamp('accepted_at')->nullable();
                $table->timestamp('picked_up_at')->nullable();
                $table->timestamp('delivered_at')->nullable();
                $table->text('delivery_notes')->nullable();
                $table->json('delivery_proof')->nullable(); // photos, signatures
                $table->decimal('delivery_fee', 8, 2)->nullable();
                $table->json('route_data')->nullable();
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
                $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
                $table->foreign('assigned_by')->references('id')->on('employees')->onDelete('cascade');
                $table->index(['driver_id', 'status']);
            });
        }

        // Employee Attendance Table (if not exists)
        if (! Schema::hasTable('employee_attendance')) {
            Schema::create('employee_attendance', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id');
                $table->date('date');
                $table->time('clock_in')->nullable();
                $table->time('clock_out')->nullable();
                $table->integer('break_minutes')->default(0);
                $table->integer('total_hours')->nullable();
                $table->enum('status', ['present', 'absent', 'late', 'half_day', 'holiday', 'sick_leave']);
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamps();

                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('employees')->onDelete('set null');
                $table->unique(['employee_id', 'date']);
            });
        }

        if (! \Illuminate\Support\Facades\Schema::hasTable('leave_requests')) {
            $exists = false;
            if (\Illuminate\Support\Facades\Schema::getConnection()->getDriverName() === 'sqlite') {
                $exists = \Illuminate\Support\Facades\DB::table('sqlite_master')
                    ->where('type', 'table')
                    ->where('name', 'leave_requests')
                    ->exists();
            }
            if (! $exists) {
                Schema::create('leave_requests', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('employee_id');
                    $table->enum('leave_type', ['annual', 'sick', 'personal', 'emergency', 'maternity', 'paternity']);
                    $table->date('start_date');
                    $table->date('end_date');
                    $table->integer('days_count');
                    $table->text('reason');
                    $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
                    $table->unsignedBigInteger('approved_by')->nullable();
                    $table->text('approval_notes')->nullable();
                    $table->timestamp('approved_at')->nullable();
                    $table->json('attachments')->nullable();
                    $table->timestamps();

                    $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
                    $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
                    $table->index(['employee_id', 'status']);
                });
            }
        }

        // Payroll Records Table (if not exists)
        if (! Schema::hasTable('payroll_records')) {
            Schema::create('payroll_records', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id');
                $table->string('pay_period'); // 2024-01, 2024-02, etc.
                $table->decimal('base_salary', 10, 2);
                $table->decimal('overtime_hours', 8, 2)->default(0);
                $table->decimal('overtime_rate', 8, 2)->default(0);
                $table->decimal('overtime_pay', 10, 2)->default(0);
                $table->decimal('bonuses', 10, 2)->default(0);
                $table->decimal('commissions', 10, 2)->default(0);
                $table->decimal('deductions', 10, 2)->default(0);
                $table->decimal('gross_pay', 10, 2);
                $table->decimal('tax_deductions', 10, 2)->default(0);
                $table->decimal('net_pay', 10, 2);
                $table->enum('status', ['draft', 'approved', 'paid']);
                $table->unsignedBigInteger('processed_by')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->json('breakdown')->nullable();
                $table->timestamps();

                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
                $table->foreign('processed_by')->references('id')->on('employees')->onDelete('set null');
                $table->unique(['employee_id', 'pay_period']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_records');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('employee_attendance');
        Schema::dropIfExists('delivery_assignments');
        Schema::dropIfExists('driver_locations');
        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('system_backups');
        Schema::dropIfExists('performance_metrics');
        Schema::dropIfExists('support_ticket_replies');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('financial_transactions');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('system_settings');
    }
};

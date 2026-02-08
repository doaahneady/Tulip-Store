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
        // Employee Attendance Table
        if (! Schema::hasTable('employee_attendance')) {
            Schema::create('employee_attendance', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id');
                $table->date('date');
                $table->time('clock_in')->nullable();
                $table->time('clock_out')->nullable();
                $table->integer('break_minutes')->default(0);
                $table->decimal('total_hours', 5, 2)->nullable();
                $table->enum('status', ['present', 'absent', 'late', 'half_day', 'holiday', 'sick_leave']);
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamps();

                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('employees')->onDelete('set null');
                $table->unique(['employee_id', 'date']);
                $table->index(['employee_id', 'date']);
            });
        }

        // Payroll Records Table
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

        // Enhanced Support Tickets Table (compatible with existing structure)
        if (! Schema::hasTable('enhanced_support_tickets')) {
            Schema::create('enhanced_support_tickets', function (Blueprint $table) {
                $table->id();
                $table->string('ticket_number')->unique();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('assigned_to')->nullable();
                $table->string('subject');
                $table->text('description');
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
                $table->enum('status', ['open', 'in_progress', 'waiting_customer', 'resolved', 'closed'])->default('open');
                $table->string('category', 50)->nullable();
                $table->json('tags')->nullable();
                $table->timestamp('first_response_at')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('assigned_to')->references('id')->on('employees')->onDelete('set null');
                $table->index(['status', 'priority']);
            });
        }

        // Enhanced Support Ticket Replies Table
        if (! Schema::hasTable('enhanced_support_ticket_replies')) {
            Schema::create('enhanced_support_ticket_replies', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ticket_id');
                $table->morphs('author'); // Can be user or employee
                $table->text('message');
                $table->json('attachments')->nullable();
                $table->boolean('is_internal')->default(false);
                $table->timestamps();

                $table->foreign('ticket_id')->references('id')->on('enhanced_support_tickets')->onDelete('cascade');
            });
        }

        // Enhanced Financial Transactions Table (compatible with existing)
        if (! Schema::hasTable('enhanced_financial_transactions')) {
            Schema::create('enhanced_financial_transactions', function (Blueprint $table) {
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

        // Enhanced Audit Logs Table (compatible with existing)
        if (! Schema::hasTable('enhanced_audit_logs')) {
            Schema::create('enhanced_audit_logs', function (Blueprint $table) {
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

        // Performance Metrics Table
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

        // System Backups Table
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

        // API Keys Table
        if (! Schema::hasTable('api_keys')) {
            Schema::create('api_keys', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('key')->unique();
                $table->string('secret')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->json('permissions')->nullable();
                $table->json('rate_limits')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['key', 'is_active']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_keys');
        Schema::dropIfExists('system_backups');
        Schema::dropIfExists('performance_metrics');
        Schema::dropIfExists('enhanced_audit_logs');
        Schema::dropIfExists('enhanced_financial_transactions');
        Schema::dropIfExists('enhanced_support_ticket_replies');
        Schema::dropIfExists('enhanced_support_tickets');
        Schema::dropIfExists('payroll_records');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('employee_attendance');
    }
};

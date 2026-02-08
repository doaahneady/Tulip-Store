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
        // Activity Feed Table (for all dashboards)
        Schema::create('activity_feeds', function (Blueprint $table) {
            $table->id();
            $table->string('dashboard_type'); // admin, it, hr, finance, vendor, supervisor
            $table->string('activity_type'); // order, user, system, employee, etc.
            $table->string('action'); // created, updated, deleted, etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->morphs('actor'); // User or Employee who performed the action
            $table->nullableMorphs('target'); // What was affected
            $table->json('metadata')->nullable();
            $table->string('severity')->default('info'); // info, warning, error, success
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['dashboard_type', 'created_at']);
            $table->index(['activity_type', 'created_at']);
            $table->index('is_read');
        });

        // Dashboard Notifications Table
        Schema::create('dashboard_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('dashboard_type');
            $table->morphs('user'); // User or Employee
            $table->string('type'); // alert, update, reminder, etc.
            $table->string('title');
            $table->text('message');
            $table->string('action_url')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->default('blue');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_type', 'user_id', 'is_read']);
            $table->index(['dashboard_type', 'created_at']);
        });

        // System Resource Monitoring (IT Dashboard)
        Schema::create('system_resources', function (Blueprint $table) {
            $table->id();
            $table->string('resource_type'); // cpu, memory, disk, network
            $table->string('server_name');
            $table->decimal('usage_percentage', 5, 2);
            $table->bigInteger('used_bytes')->nullable();
            $table->bigInteger('total_bytes')->nullable();
            $table->json('details')->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->index(['resource_type', 'server_name', 'recorded_at']);
        });

        // Automated Alert Rules (IT Dashboard)
        Schema::create('alert_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('dashboard_type');
            $table->string('metric_type'); // cpu, memory, error_rate, etc.
            $table->string('condition'); // >, <, >=, <=, ==
            $table->decimal('threshold_value', 10, 2);
            $table->integer('duration_minutes')->default(5);
            $table->string('severity')->default('warning');
            $table->boolean('is_active')->default(true);
            $table->json('notification_channels')->nullable(); // email, sms, dashboard
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['dashboard_type', 'is_active']);
        });

        // Security Audit Logs (IT Dashboard)
        Schema::create('security_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_type'); // login_attempt, permission_change, data_access, etc.
            $table->morphs('user');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('status'); // success, failed, blocked
            $table->text('description');
            $table->json('metadata')->nullable();
            $table->string('risk_level')->default('low'); // low, medium, high, critical
            $table->timestamps();

            $table->index(['event_type', 'created_at']);
            $table->index(['user_type', 'user_id', 'created_at']);
            $table->index('risk_level');
        });

        // Employee Onboarding Workflow (HR Dashboard)
        Schema::create('onboarding_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('task_name');
            $table->text('description')->nullable();
            $table->string('category'); // paperwork, training, equipment, etc.
            $table->string('status')->default('pending'); // pending, in_progress, completed, skipped
            $table->integer('order')->default(0);
            $table->date('due_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('employees')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'status']);
        });

        // Employee Engagement Metrics (HR Dashboard)
        Schema::create('employee_engagement_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('survey_period'); // 2025-Q1, 2025-01, etc.
            $table->integer('job_satisfaction')->nullable(); // 1-5
            $table->integer('work_life_balance')->nullable();
            $table->integer('management_rating')->nullable();
            $table->integer('team_collaboration')->nullable();
            $table->integer('career_growth')->nullable();
            $table->decimal('overall_score', 3, 2);
            $table->text('comments')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'survey_period']);
        });

        // Training and Certification Tracking (HR Dashboard)
        Schema::create('employee_training_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('training_name');
            $table->string('training_type'); // internal, external, certification, etc.
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('status'); // scheduled, in_progress, completed, expired
            $table->string('certificate_number')->nullable();
            $table->string('provider')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'status']);
            $table->index('expiry_date');
        });

        // Leave Balance Tracking (HR Dashboard)
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('leave_type'); // annual, sick, personal, etc.
            $table->integer('year');
            $table->decimal('allocated_days', 5, 2);
            $table->decimal('used_days', 5, 2)->default(0);
            $table->decimal('remaining_days', 5, 2);
            $table->decimal('carried_over', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['employee_id', 'leave_type', 'year']);
            $table->index(['employee_id', 'year']);
        });

        // Budget vs Actual Tracking (Finance Dashboard)
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('budget_name');
            $table->string('category'); // revenue, expense, department, project, etc.
            $table->string('period_type'); // monthly, quarterly, yearly
            $table->string('period'); // 2025-01, 2025-Q1, 2025, etc.
            $table->decimal('budgeted_amount', 12, 2);
            $table->decimal('actual_amount', 12, 2)->default(0);
            $table->decimal('variance', 12, 2)->default(0);
            $table->decimal('variance_percentage', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->timestamps();

            $table->index(['category', 'period']);
        });

        // Profit & Loss Statements (Finance Dashboard)
        Schema::create('profit_loss_statements', function (Blueprint $table) {
            $table->id();
            $table->string('period_type'); // monthly, quarterly, yearly
            $table->string('period'); // 2025-01, 2025-Q1, 2025
            $table->decimal('total_revenue', 12, 2);
            $table->decimal('cost_of_goods_sold', 12, 2);
            $table->decimal('gross_profit', 12, 2);
            $table->decimal('operating_expenses', 12, 2);
            $table->decimal('operating_profit', 12, 2);
            $table->decimal('other_income', 12, 2)->default(0);
            $table->decimal('other_expenses', 12, 2)->default(0);
            $table->decimal('net_profit', 12, 2);
            $table->decimal('tax_expense', 12, 2)->default(0);
            $table->decimal('net_profit_after_tax', 12, 2);
            $table->json('breakdown')->nullable();
            $table->timestamps();

            $table->unique(['period_type', 'period']);
        });

        // Cash Flow Tracking (Finance Dashboard)
        Schema::create('cash_flow_records', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->string('flow_type'); // inflow, outflow
            $table->string('category'); // sales, expenses, investments, loans, etc.
            $table->decimal('amount', 12, 2);
            $table->string('description');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('balance_after', 12, 2);
            $table->timestamps();

            $table->index(['transaction_date', 'flow_type']);
            $table->index(['category', 'transaction_date']);
        });

        // Financial Forecasting (Finance Dashboard)
        Schema::create('financial_forecasts', function (Blueprint $table) {
            $table->id();
            $table->string('forecast_type'); // revenue, expense, profit
            $table->string('period_type'); // monthly, quarterly, yearly
            $table->string('period'); // 2025-02, 2025-Q2, 2026
            $table->decimal('forecasted_amount', 12, 2);
            $table->decimal('confidence_level', 5, 2); // 0-100
            $table->json('assumptions')->nullable();
            $table->string('method'); // linear, exponential, seasonal, etc.
            $table->foreignId('created_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->timestamps();

            $table->index(['forecast_type', 'period']);
        });

        // Inventory Alerts (Vendor Dashboard)
        Schema::create('inventory_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('alert_type'); // low_stock, out_of_stock, overstock, expiry
            $table->integer('current_quantity');
            $table->integer('threshold_quantity');
            $table->string('severity')->default('warning'); // info, warning, critical
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'is_resolved']);
            $table->index('alert_type');
        });

        // Sales Forecasting (Vendor Dashboard)
        Schema::create('sales_forecasts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('forecast_period'); // 2025-02, 2025-Q2
            $table->integer('forecasted_quantity');
            $table->decimal('forecasted_revenue', 12, 2);
            $table->decimal('confidence_score', 5, 2); // 0-100
            $table->json('factors')->nullable(); // seasonality, trends, promotions, etc.
            $table->timestamps();

            $table->index(['store_id', 'forecast_period']);
            $table->index(['product_id', 'forecast_period']);
        });

        // Product Performance Analytics (Vendor Dashboard)
        Schema::create('product_performance_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->date('metric_date');
            $table->integer('views')->default(0);
            $table->integer('cart_additions')->default(0);
            $table->integer('purchases')->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0);
            $table->decimal('revenue', 10, 2)->default(0);
            $table->decimal('average_rating', 3, 2)->nullable();
            $table->integer('review_count')->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'metric_date']);
            $table->index('metric_date');
        });

        // Route Optimization Results (Supervisor Dashboard)
        Schema::create('route_optimizations', function (Blueprint $table) {
            $table->id();
            $table->string('optimization_date');
            $table->json('delivery_ids'); // Array of order IDs
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('set null');
            $table->decimal('total_distance_km', 10, 2);
            $table->integer('estimated_duration_minutes');
            $table->decimal('fuel_cost', 10, 2)->nullable();
            $table->json('route_path')->nullable(); // Coordinates
            $table->string('status'); // pending, optimized, assigned, completed
            $table->decimal('savings_percentage', 5, 2)->nullable(); // vs non-optimized
            $table->timestamps();

            $table->index(['driver_id', 'status']);
            $table->index('optimization_date');
        });

        // Driver Performance Scores (Supervisor Dashboard)
        Schema::create('driver_performance_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->string('period'); // 2025-01, 2025-Q1, 2025
            $table->integer('total_deliveries');
            $table->integer('on_time_deliveries');
            $table->decimal('on_time_rate', 5, 2);
            $table->decimal('average_delivery_time_minutes', 8, 2);
            $table->decimal('customer_rating', 3, 2);
            $table->integer('accidents')->default(0);
            $table->integer('violations')->default(0);
            $table->decimal('overall_score', 5, 2);
            $table->string('performance_grade'); // A, B, C, D, F
            $table->timestamps();

            $table->unique(['driver_id', 'period']);
            $table->index('overall_score');
        });

        // Delivery Zone Analytics (Supervisor Dashboard)
        Schema::create('delivery_zone_analytics', function (Blueprint $table) {
            $table->id();
            $table->string('zone_name');
            $table->date('analytics_date');
            $table->integer('total_deliveries');
            $table->integer('completed_deliveries');
            $table->integer('failed_deliveries');
            $table->decimal('average_delivery_time_minutes', 8, 2);
            $table->decimal('average_delivery_cost', 10, 2);
            $table->decimal('customer_satisfaction_score', 3, 2)->nullable();
            $table->json('peak_hours')->nullable();
            $table->timestamps();

            $table->unique(['zone_name', 'analytics_date']);
            $table->index('analytics_date');
        });

        // Quick Actions Log (All Dashboards)
        if (! Schema::hasTable('dashboard_quick_actions')) {
            Schema::create('dashboard_quick_actions', function (Blueprint $table) {
                $table->id();
                $table->string('dashboard_type');
                $table->string('action_type');
                $table->morphs('user');
                $table->text('description');
                $table->integer('affected_records')->default(0);
                $table->string('status');
                $table->text('error_message')->nullable();
                $table->json('parameters')->nullable();
                $table->timestamps();

                $table->index(['dashboard_type', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_quick_actions');
        Schema::dropIfExists('delivery_zone_analytics');
        Schema::dropIfExists('driver_performance_scores');
        Schema::dropIfExists('route_optimizations');
        Schema::dropIfExists('product_performance_metrics');
        Schema::dropIfExists('sales_forecasts');
        Schema::dropIfExists('inventory_alerts');
        Schema::dropIfExists('financial_forecasts');
        Schema::dropIfExists('cash_flow_records');
        Schema::dropIfExists('profit_loss_statements');
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('leave_balances');
        Schema::dropIfExists('employee_training_records');
        Schema::dropIfExists('employee_engagement_surveys');
        Schema::dropIfExists('onboarding_tasks');
        Schema::dropIfExists('security_audit_logs');
        Schema::dropIfExists('alert_rules');
        Schema::dropIfExists('system_resources');
        Schema::dropIfExists('dashboard_notifications');
        Schema::dropIfExists('activity_feeds');
    }
};

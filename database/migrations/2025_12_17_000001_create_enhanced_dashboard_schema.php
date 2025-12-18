<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Enhanced Database Schema for 6-Dashboard System
     * Adds missing tables and optimizations for dashboard-specific features
     */
    public function up()
    {
        // =====================================================
        // ENHANCED GEOSPATIAL SUPPORT
        // =====================================================
        
        // Add current location to drivers table for quick queries
        Schema::table('drivers', function (Blueprint $table) {
            $table->point('last_location')->nullable()->after('working_hours');
            $table->timestamp('last_location_update')->nullable()->after('last_location');
            $table->decimal('current_speed', 8, 2)->nullable()->after('last_location_update');
            $table->decimal('current_heading', 8, 2)->nullable()->after('current_speed');
            
            // Add spatial index for location queries
            $table->spatialIndex('last_location');
        });

        // Route optimization table
        Schema::create('delivery_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->date('route_date');
            $table->json('waypoints'); // Array of delivery stops with coordinates
            $table->json('optimized_sequence'); // Optimized order of deliveries
            $table->decimal('total_distance', 10, 2)->nullable(); // Kilometers
            $table->integer('estimated_duration')->nullable(); // Minutes
            $table->enum('status', ['planned', 'active', 'completed', 'cancelled'])->default('planned');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['driver_id', 'route_date']);
            $table->index(['status', 'route_date']);
        });

        // Vehicle maintenance tracking
        Schema::create('vehicle_maintenance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['routine', 'repair', 'inspection', 'emergency']);
            $table->string('description');
            $table->decimal('cost', 10, 2)->nullable();
            $table->date('maintenance_date');
            $table->date('next_due_date')->nullable();
            $table->integer('odometer_reading')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable(); // Photos, receipts
            $table->timestamps();
            
            $table->index(['driver_id', 'maintenance_date']);
            $table->index(['type', 'status']);
        });

        // =====================================================
        // ENHANCED HR SYSTEM
        // =====================================================
        
        // Performance reviews
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->string('review_period'); // Q1-2024, 2024-Annual, etc.
            $table->enum('type', ['quarterly', 'annual', 'probation', 'special']);
            $table->json('ratings'); // Performance categories and scores
            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('goals')->nullable();
            $table->decimal('overall_rating', 3, 2); // 1.00 to 5.00
            $table->enum('status', ['draft', 'submitted', 'approved', 'completed'])->default('draft');
            $table->timestamp('review_date');
            $table->timestamps();
            
            $table->index(['employee_id', 'review_period']);
            $table->index(['reviewer_id', 'status']);
        });

        // Job applications and recruiting
        Schema::create('job_positions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('department');
            $table->text('description');
            $table->json('requirements'); // Skills, experience, education
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'intern']);
            $table->enum('status', ['draft', 'active', 'paused', 'closed'])->default('draft');
            $table->foreignId('hiring_manager_id')->constrained('users')->onDelete('cascade');
            $table->date('application_deadline')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'department']);
            $table->index(['hiring_manager_id', 'status']);
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->constrained('job_positions')->onDelete('cascade');
            $table->string('applicant_name');
            $table->string('applicant_email');
            $table->string('applicant_phone')->nullable();
            $table->json('resume_data'); // Parsed resume information
            $table->text('cover_letter')->nullable();
            $table->json('attachments'); // Resume, portfolio files
            $table->enum('status', [
                'applied', 'screening', 'interview_scheduled', 
                'interviewed', 'offer_made', 'hired', 'rejected'
            ])->default('applied');
            $table->json('interview_notes')->nullable();
            $table->decimal('rating', 3, 2)->nullable(); // Interview rating
            $table->timestamps();
            
            $table->index(['position_id', 'status']);
            $table->index(['applicant_email', 'status']);
        });

        // Internal announcements
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['general', 'policy', 'event', 'urgent', 'celebration']);
            $table->enum('target_audience', ['all', 'department', 'role', 'specific_users']);
            $table->json('target_criteria')->nullable(); // Department, role, or user IDs
            $table->boolean('is_pinned')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['type', 'published_at']);
            $table->index(['target_audience', 'is_pinned']);
        });

        // =====================================================
        // ENHANCED IT/DEVOPS MONITORING
        // =====================================================
        
        // System alerts and monitoring
        Schema::create('system_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('alert_type'); // cpu_high, disk_full, service_down, etc.
            $table->enum('severity', ['info', 'warning', 'critical', 'emergency']);
            $table->string('title');
            $table->text('description');
            $table->json('metadata'); // Service details, metrics, etc.
            $table->enum('status', ['active', 'acknowledged', 'resolved', 'suppressed'])->default('active');
            $table->foreignId('acknowledged_by')->nullable()->constrained('users');
            $table->timestamp('acknowledged_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users');
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
            
            $table->index(['severity', 'status']);
            $table->index(['alert_type', 'created_at']);
        });

        // Database backup tracking
        Schema::create('database_backups', function (Blueprint $table) {
            $table->id();
            $table->string('backup_name');
            $table->string('database_name');
            $table->enum('type', ['full', 'incremental', 'differential']);
            $table->bigInteger('file_size'); // Bytes
            $table->string('file_path');
            $table->string('checksum')->nullable(); // For integrity verification
            $table->enum('status', ['in_progress', 'completed', 'failed', 'corrupted']);
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            $table->index(['database_name', 'status']);
            $table->index(['type', 'completed_at']);
        });

        // API error tracking
        Schema::create('api_errors', function (Blueprint $table) {
            $table->id();
            $table->string('endpoint');
            $table->string('method'); // GET, POST, PUT, DELETE
            $table->integer('status_code');
            $table->text('error_message');
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->string('user_id')->nullable();
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->decimal('response_time', 8, 3); // Milliseconds
            $table->timestamp('occurred_at')->useCurrent();
            
            $table->index(['endpoint', 'status_code']);
            $table->index(['occurred_at', 'status_code']);
            $table->index(['user_id', 'occurred_at']);
        });

        // Slow query tracking
        Schema::create('slow_queries', function (Blueprint $table) {
            $table->id();
            $table->text('query_sql');
            $table->string('query_hash'); // MD5 hash for grouping similar queries
            $table->decimal('execution_time', 10, 3); // Seconds
            $table->integer('rows_examined')->nullable();
            $table->integer('rows_sent')->nullable();
            $table->string('database_name');
            $table->string('user_name')->nullable();
            $table->string('host')->nullable();
            $table->timestamp('executed_at')->useCurrent();
            
            $table->index(['query_hash', 'execution_time']);
            $table->index(['execution_time', 'executed_at']);
            $table->index(['database_name', 'executed_at']);
        });

        // =====================================================
        // ENHANCED FINANCIAL SYSTEM
        // =====================================================
        
        // Commission tracking per store
        Schema::create('commission_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->decimal('rate', 5, 4); // 0.0500 = 5%
            $table->decimal('minimum_amount', 10, 2)->nullable(); // Minimum order for commission
            $table->json('category_rates')->nullable(); // Different rates per category
            $table->date('effective_from');
            $table->date('effective_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['store_id', 'effective_from']);
            $table->index(['is_active', 'effective_from']);
        });

        // Tax calculations and compliance
        Schema::create('tax_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('transaction_id')->nullable()->constrained('financial_transactions')->onDelete('cascade');
            $table->string('tax_type'); // vat, sales_tax, service_tax, etc.
            $table->decimal('tax_rate', 5, 4); // 0.1500 = 15%
            $table->decimal('taxable_amount', 12, 2);
            $table->decimal('tax_amount', 12, 2);
            $table->string('tax_jurisdiction')->nullable(); // Country, state, city
            $table->json('calculation_details')->nullable(); // Breakdown of calculation
            $table->timestamps();
            
            $table->index(['order_id', 'tax_type']);
            $table->index(['tax_type', 'created_at']);
        });

        // Financial reconciliation
        Schema::create('financial_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->date('reconciliation_date');
            $table->string('account_type'); // bank, payment_gateway, cash, etc.
            $table->string('account_identifier'); // Account number, gateway ID
            $table->decimal('system_balance', 15, 2);
            $table->decimal('external_balance', 15, 2);
            $table->decimal('difference', 15, 2);
            $table->enum('status', ['pending', 'reconciled', 'discrepancy', 'investigating']);
            $table->json('discrepancy_details')->nullable();
            $table->foreignId('reconciled_by')->nullable()->constrained('users');
            $table->timestamp('reconciled_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['reconciliation_date', 'status']);
            $table->index(['account_type', 'reconciliation_date']);
        });

        // =====================================================
        // ENHANCED ANALYTICS & REPORTING
        // =====================================================
        
        // Materialized views for dashboard performance
        Schema::create('daily_analytics', function (Blueprint $table) {
            $table->id();
            $table->date('analytics_date');
            $table->string('metric_type'); // revenue, orders, users, etc.
            $table->string('dimension')->nullable(); // store_id, category_id, etc.
            $table->string('dimension_value')->nullable();
            $table->decimal('metric_value', 15, 2);
            $table->json('additional_data')->nullable();
            $table->timestamp('calculated_at')->useCurrent();
            
            $table->unique(['analytics_date', 'metric_type', 'dimension', 'dimension_value']);
            $table->index(['analytics_date', 'metric_type']);
        });

        // Real-time dashboard cache
        Schema::create('dashboard_cache', function (Blueprint $table) {
            $table->id();
            $table->string('dashboard_type'); // admin, finance, vendor, etc.
            $table->string('cache_key');
            $table->json('cache_data');
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->unique(['dashboard_type', 'cache_key']);
            $table->index(['expires_at']);
        });

        // =====================================================
        // ENHANCED NOTIFICATION SYSTEM
        // =====================================================
        
        // Notification preferences per user
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('notification_type'); // order_update, system_alert, etc.
            $table->json('channels'); // ['email', 'sms', 'push', 'database']
            $table->boolean('is_enabled')->default(true);
            $table->json('schedule')->nullable(); // Time preferences, frequency
            $table->timestamps();
            
            $table->unique(['user_id', 'notification_type']);
        });

        // Notification templates
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('type'); // order_update, payment_received, etc.
            $table->string('channel'); // email, sms, push
            $table->string('subject')->nullable();
            $table->text('template');
            $table->json('variables')->nullable(); // Available template variables
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['type', 'channel']);
        });

        // =====================================================
        // PERFORMANCE OPTIMIZATION INDEXES
        // =====================================================
        
        // Add composite indexes for common dashboard queries
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['store_id', 'status', 'created_at'], 'orders_store_status_date');
            $table->index(['payment_status', 'created_at'], 'orders_payment_date');
            $table->index(['status', 'estimated_delivery'], 'orders_status_delivery');
        });

        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->index(['store_id', 'type', 'status'], 'transactions_store_type_status');
            $table->index(['approval_status', 'created_at'], 'transactions_approval_date');
            $table->index(['type', 'created_at'], 'transactions_type_date');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index(['store_id', 'is_active', 'created_at'], 'products_store_active_date');
            $table->index(['stock_quantity', 'low_stock_threshold'], 'products_stock_levels');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->index(['department', 'status', 'hire_date'], 'employees_dept_status_hire');
        });

        Schema::table('shifts', function (Blueprint $table) {
            $table->index(['shift_date', 'status'], 'shifts_date_status');
            $table->index(['employee_id', 'shift_date', 'status'], 'shifts_employee_date_status');
        });
    }

    public function down()
    {
        // Drop new tables
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('dashboard_cache');
        Schema::dropIfExists('daily_analytics');
        Schema::dropIfExists('financial_reconciliations');
        Schema::dropIfExists('tax_calculations');
        Schema::dropIfExists('commission_rates');
        Schema::dropIfExists('slow_queries');
        Schema::dropIfExists('api_errors');
        Schema::dropIfExists('database_backups');
        Schema::dropIfExists('system_alerts');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('job_positions');
        Schema::dropIfExists('performance_reviews');
        Schema::dropIfExists('vehicle_maintenance');
        Schema::dropIfExists('delivery_routes');

        // Remove added columns from existing tables
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropSpatialIndex(['last_location']);
            $table->dropColumn(['last_location', 'last_location_update', 'current_speed', 'current_heading']);
        });
    }
};
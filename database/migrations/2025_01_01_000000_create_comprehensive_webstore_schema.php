<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Comprehensive Webstore Platform Database Schema
     * Designed for 6 distinct user roles with enterprise-grade features
     */
    public function up()
    {
        // =====================================================
        // CORE RBAC SYSTEM
        // =====================================================

        // Roles table - Define system roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // super_admin, it_admin, hr_manager, etc.
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->json('permissions')->nullable(); // JSON array of permissions
            $table->boolean('is_system_role')->default(false); // Cannot be deleted
            $table->timestamps();

            $table->index(['name', 'is_system_role']);
        });

        // Permissions table - Granular permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // users.create, orders.read, etc.
            $table->string('display_name');
            $table->string('category'); // users, orders, finance, etc.
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['category', 'name']);
        });

        // Role-Permission pivot table
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['role_id', 'permission_id']);
        });

        // User-Role assignments (many-to-many for multiple roles)
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->timestamp('assigned_at')->useCurrent();
            $table->foreignId('assigned_by')->nullable()->constrained('users');
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);

            $table->unique(['user_id', 'role_id']);
            $table->index(['user_id', 'is_active']);
        });

        // =====================================================
        // AUDIT & ACTIVITY LOGGING
        // =====================================================

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // create, update, delete, login, etc.
            $table->string('model_type')->nullable(); // User, Order, Product, etc.
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('session_id')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'created_at']);
            $table->index(['model_type', 'model_id']);
            $table->index(['action', 'created_at']);
        });

        // =====================================================
        // ORGANIZATIONS & STORES
        // =====================================================

        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->json('settings')->nullable(); // Organization-specific settings
            $table->enum('status', ['active', 'suspended', 'inactive'])->default('active');
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->json('business_info')->nullable(); // Tax ID, registration, etc.
            $table->json('contact_info')->nullable(); // Phone, email, address
            $table->json('settings')->nullable(); // Store-specific settings
            $table->enum('status', ['active', 'pending', 'suspended', 'closed'])->default('pending');
            $table->decimal('commission_rate', 5, 4)->default(0.0500); // 5% default
            $table->timestamps();

            $table->index(['owner_id', 'status']);
            $table->index(['organization_id', 'status']);
        });

        // =====================================================
        // PRODUCT MANAGEMENT
        // =====================================================

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // SEO, filters, etc.
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
            $table->index(['parent_id', 'is_active', 'sort_order']);
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('sku')->unique();
            $table->decimal('price', 10, 2);
            $table->decimal('cost_price', 10, 2)->nullable(); // For profit calculations
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(10);
            $table->json('images')->nullable(); // Array of image URLs
            $table->json('attributes')->nullable(); // Size, color, weight, etc.
            $table->json('seo_data')->nullable(); // Meta title, description, keywords
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('track_inventory')->default(true);
            $table->enum('status', ['draft', 'active', 'inactive', 'out_of_stock'])->default('draft');
            $table->decimal('weight', 8, 2)->nullable(); // For shipping calculations
            $table->json('dimensions')->nullable(); // L x W x H
            $table->timestamps();

            $table->index(['store_id', 'status']);
            $table->index(['category_id', 'is_active']);
            $table->index(['sku', 'is_active']);
            $table->index(['stock_quantity', 'low_stock_threshold']);
        });

        // =====================================================
        // ORDER MANAGEMENT SYSTEM
        // =====================================================

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('set null');

            // Order Status Flow
            $table->enum('status', [
                'pending', 'confirmed', 'processing', 'shipped',
                'delivered', 'cancelled', 'refunded', 'returned',
            ])->default('pending');

            // Payment Information
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'partial'])->default('pending');
            $table->string('payment_method')->nullable(); // card, cash, wallet, etc.
            $table->string('payment_reference')->nullable();

            // Financial Breakdown
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('commission_amount', 10, 2)->default(0); // Platform commission

            // Shipping Information
            $table->json('shipping_address');
            $table->json('billing_address')->nullable();
            $table->timestamp('estimated_delivery')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            // Tracking & Notes
            $table->string('tracking_number')->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();

            $table->timestamps();

            $table->index(['customer_id', 'status']);
            $table->index(['store_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index(['payment_status', 'created_at']);
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // Snapshot at time of order
            $table->string('product_sku'); // Snapshot at time of order
            $table->decimal('unit_price', 10, 2); // Price at time of order
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2); // unit_price * quantity
            $table->json('product_snapshot')->nullable(); // Full product data at time of order
            $table->timestamps();

            $table->index(['order_id', 'product_id']);
        });

        // =====================================================
        // DELIVERY & LOGISTICS SYSTEM
        // =====================================================

        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('license_number')->unique();
            $table->date('license_expiry');
            $table->string('vehicle_type'); // motorcycle, car, truck
            $table->string('vehicle_plate')->unique();
            $table->json('vehicle_info')->nullable(); // Make, model, year, etc.
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->enum('availability', ['available', 'busy', 'offline'])->default('offline');
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->integer('total_deliveries')->default(0);
            $table->json('working_hours')->nullable(); // Schedule preferences
            $table->timestamps();

            $table->index(['status', 'availability']);
            $table->index(['rating', 'total_deliveries']);
        });

        // Real-time location tracking with PostGIS support
        Schema::create('driver_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('accuracy', 8, 2)->nullable(); // GPS accuracy in meters
            $table->decimal('speed', 8, 2)->nullable(); // Speed in km/h
            $table->decimal('heading', 8, 2)->nullable(); // Direction in degrees
            $table->timestamp('recorded_at')->useCurrent();

            // PostGIS geometry column (if PostGIS is available)
            // $table->geometry('location', 'POINT', 4326)->nullable();

            $table->index(['driver_id', 'recorded_at']);
            $table->index(['latitude', 'longitude']);
        });

        Schema::create('delivery_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');

            $table->enum('status', [
                'assigned', 'accepted', 'rejected', 'picked_up',
                'in_transit', 'delivered', 'failed', 'cancelled',
            ])->default('assigned');

            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            $table->text('driver_notes')->nullable();
            $table->json('delivery_proof')->nullable(); // Photos, signatures, etc.
            $table->decimal('delivery_fee', 8, 2)->nullable();

            $table->timestamps();

            $table->index(['driver_id', 'status']);
            $table->index(['order_id', 'status']);
        });

        // =====================================================
        // FINANCIAL SYSTEM
        // =====================================================

        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique(); // External reference
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('set null');

            $table->enum('type', [
                'order_payment', 'commission', 'payout', 'refund',
                'fee', 'adjustment', 'payroll', 'salary_payment', 'expense',
            ]);

            $table->enum('status', [
                'pending',
                'pending_approval',
                'approved',
                'rejected',
                'processing',
                'completed',
                'failed',
                'cancelled',
            ])->default('pending');

            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->text('description');
            $table->json('metadata')->nullable(); // Additional transaction data

            // Immutability fields
            $table->string('hash')->nullable(); // For transaction integrity
            $table->boolean('is_locked')->default(false); // Prevent modifications
            $table->timestamp('locked_at')->nullable();

            // Approval workflow
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();

            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index(['user_id', 'created_at']);
            $table->index(['store_id', 'type']);
            $table->index(['approval_status', 'created_at']);
        });

        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'approved', 'processing', 'completed', 'rejected'])->default('pending');
            $table->json('bank_details'); // Account info for payout
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamp('processed_at')->nullable();
            $table->string('reference_number')->nullable(); // Bank reference
            $table->timestamps();

            $table->index(['store_id', 'status']);
            $table->index(['status', 'created_at']);
        });

        // =====================================================
        // HR MANAGEMENT SYSTEM
        // =====================================================

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_id')->unique();
            $table->string('department');
            $table->string('position');
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->decimal('monthly_salary', 10, 2)->nullable();
            $table->date('hire_date');
            $table->date('termination_date')->nullable();
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'intern']);
            $table->enum('status', ['active', 'inactive', 'terminated', 'on_leave'])->default('active');
            $table->json('emergency_contact')->nullable();
            $table->json('documents')->nullable(); // Contract, ID copies, etc.
            $table->timestamps();

            $table->index(['department', 'status']);
            $table->index(['status', 'hire_date']);
        });

        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('shift_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->time('actual_start_time')->nullable();
            $table->time('actual_end_time')->nullable();
            $table->decimal('break_duration', 4, 2)->default(0); // Hours
            $table->decimal('hours_worked', 4, 2)->nullable();
            $table->decimal('overtime_hours', 4, 2)->default(0);
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'missed', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'shift_date']);
            $table->index(['shift_date', 'status']);
        });

        Schema::create('payroll_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('pay_period'); // 2024-01, 2024-W01, etc.
            $table->decimal('regular_hours', 6, 2)->default(0);
            $table->decimal('overtime_hours', 6, 2)->default(0);
            $table->decimal('regular_pay', 10, 2)->default(0);
            $table->decimal('overtime_pay', 10, 2)->default(0);
            $table->decimal('bonuses', 10, 2)->default(0);
            $table->decimal('deductions', 10, 2)->default(0);
            $table->decimal('gross_pay', 10, 2);
            $table->decimal('net_pay', 10, 2);
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'pay_period']);
            $table->index(['pay_period', 'status']);
        });

        // =====================================================
        // IT/DEVOPS MONITORING SYSTEM
        // =====================================================

        Schema::create('system_services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // web_server, database, redis, etc.
            $table->string('type'); // service, database, cache, etc.
            $table->string('host');
            $table->integer('port')->nullable();
            $table->enum('status', ['online', 'offline', 'degraded', 'maintenance'])->default('offline');
            $table->decimal('response_time', 8, 3)->nullable(); // milliseconds
            $table->integer('uptime_percentage')->default(0); // 0-100
            $table->timestamp('last_check')->nullable();
            $table->json('health_data')->nullable(); // CPU, memory, disk usage
            $table->json('configuration')->nullable();
            $table->timestamps();

            $table->index(['status', 'last_check']);
            $table->index(['type', 'status']);
        });

        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('level', ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug']);
            $table->string('channel')->nullable(); // application, database, security, etc.
            $table->text('message');
            $table->json('context')->nullable(); // Additional log data
            $table->string('file')->nullable();
            $table->integer('line')->nullable();
            $table->string('user_id')->nullable();
            $table->string('session_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['level', 'created_at']);
            $table->index(['channel', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });

        Schema::create('deployment_logs', function (Blueprint $table) {
            $table->id();
            $table->string('version');
            $table->string('environment'); // production, staging, development
            $table->enum('status', ['pending', 'in_progress', 'completed', 'failed', 'rolled_back']);
            $table->foreignId('deployed_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->json('changes')->nullable(); // List of changes/features
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['environment', 'status']);
            $table->index(['version', 'environment']);
        });

        // =====================================================
        // CUSTOMER SUPPORT SYSTEM
        // =====================================================

        if (! Schema::hasTable('support_tickets')) {
            Schema::create('support_tickets', function (Blueprint $table) {
                $table->id();
                $table->string('ticket_number')->unique();
                $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
                $table->string('subject');
                $table->text('description');
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
                $table->enum('status', ['open', 'in_progress', 'waiting_customer', 'resolved', 'closed'])->default('open');
                $table->string('category')->nullable();
                $table->foreignId('related_order_id')->nullable()->constrained('orders')->onDelete('set null');
                $table->timestamp('first_response_at')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();

                $table->index(['status', 'priority']);
                $table->index(['assigned_to', 'status']);
                $table->index(['customer_id', 'status']);
            });
        }

        if (! Schema::hasTable('ticket_replies')) {
            Schema::create('ticket_replies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ticket_id')->constrained('support_tickets')->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('message');
                $table->json('attachments')->nullable();
                $table->boolean('is_internal')->default(false);
                $table->timestamps();

                $table->index(['ticket_id', 'created_at']);
            });
        }

        // =====================================================
        // NOTIFICATION SYSTEM
        // =====================================================

        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('type'); // order_update, system_alert, etc.
                $table->string('title');
                $table->text('message');
                $table->json('data')->nullable(); // Additional notification data
                $table->enum('channel', ['database', 'email', 'sms', 'push'])->default('database');
                $table->boolean('is_read')->default(false);
                $table->timestamp('read_at')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'is_read']);
                $table->index(['type', 'created_at']);
            });
        }

        // =====================================================
        // SETTINGS & CONFIGURATION
        // =====================================================

        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false); // Can be accessed by frontend
            $table->timestamps();

            $table->index(['key', 'is_public']);
        });
    }

    public function down()
    {
        // Drop tables in reverse order to handle foreign key constraints
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('deployment_logs');
        Schema::dropIfExists('system_logs');
        Schema::dropIfExists('system_services');
        Schema::dropIfExists('payroll_records');
        Schema::dropIfExists('shifts');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('payouts');
        Schema::dropIfExists('financial_transactions');
        Schema::dropIfExists('delivery_assignments');
        Schema::dropIfExists('driver_locations');
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('stores');
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};

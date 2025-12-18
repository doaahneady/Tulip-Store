<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Update existing tables for the 6-dashboard system
     */
    public function up()
    {
        // =====================================================
        // UPDATE EXISTING TABLES
        // =====================================================
        
        // Update drivers table if it exists
        if (Schema::hasTable('drivers')) {
            Schema::table('drivers', function (Blueprint $table) {
                if (!Schema::hasColumn('drivers', 'working_hours')) {
                    $table->json('working_hours')->nullable();
                }
                if (!Schema::hasColumn('drivers', 'last_location')) {
                    $table->point('last_location')->nullable();
                }
                if (!Schema::hasColumn('drivers', 'last_location_update')) {
                    $table->timestamp('last_location_update')->nullable();
                }
                if (!Schema::hasColumn('drivers', 'current_speed')) {
                    $table->decimal('current_speed', 8, 2)->nullable();
                }
                if (!Schema::hasColumn('drivers', 'current_heading')) {
                    $table->decimal('current_heading', 8, 2)->nullable();
                }
            });
        }

        // Update users table for enhanced RBAC
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'status')) {
                    $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
                }
            });
        }

        // Update orders table if needed
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'commission_amount')) {
                    $table->decimal('commission_amount', 10, 2)->default(0);
                }
            });
        }

        // Update products table if needed
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'cost_price')) {
                    $table->decimal('cost_price', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('products', 'low_stock_threshold')) {
                    $table->integer('low_stock_threshold')->default(10);
                }
                if (!Schema::hasColumn('products', 'track_inventory')) {
                    $table->boolean('track_inventory')->default(true);
                }
                if (!Schema::hasColumn('products', 'weight')) {
                    $table->decimal('weight', 8, 2)->nullable();
                }
                if (!Schema::hasColumn('products', 'dimensions')) {
                    $table->json('dimensions')->nullable();
                }
            });
        }

        // Update stores table if needed
        if (Schema::hasTable('stores')) {
            Schema::table('stores', function (Blueprint $table) {
                if (!Schema::hasColumn('stores', 'total_earnings')) {
                    $table->decimal('total_earnings', 15, 2)->default(0);
                }
                if (!Schema::hasColumn('stores', 'available_balance')) {
                    $table->decimal('available_balance', 15, 2)->default(0);
                }
                if (!Schema::hasColumn('stores', 'pending_payout')) {
                    $table->decimal('pending_payout', 15, 2)->default(0);
                }
                if (!Schema::hasColumn('stores', 'total_orders')) {
                    $table->integer('total_orders')->default(0);
                }
                if (!Schema::hasColumn('stores', 'last_order_at')) {
                    $table->timestamp('last_order_at')->nullable();
                }
            });
        }

        // =====================================================
        // CREATE NEW TABLES THAT DON'T EXIST
        // =====================================================
        
        // Create shifts table if it doesn't exist
        if (!Schema::hasTable('shifts')) {
            Schema::create('shifts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained()->onDelete('cascade');
                $table->date('shift_date');
                $table->time('start_time');
                $table->time('end_time');
                $table->time('actual_start_time')->nullable();
                $table->time('actual_end_time')->nullable();
                $table->decimal('break_duration', 4, 2)->default(0);
                $table->decimal('hours_worked', 4, 2)->nullable();
                $table->decimal('overtime_hours', 4, 2)->default(0);
                $table->enum('status', ['scheduled', 'in_progress', 'completed', 'missed', 'cancelled'])->default('scheduled');
                $table->text('notes')->nullable();
                $table->timestamps();
                
                $table->index(['employee_id', 'shift_date']);
                $table->index(['shift_date', 'status']);
            });
        }

        // Create payroll_records table if it doesn't exist
        if (!Schema::hasTable('payroll_records')) {
            Schema::create('payroll_records', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained()->onDelete('cascade');
                $table->string('pay_period');
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
        }

        // Create job_positions table if it doesn't exist
        if (!Schema::hasTable('job_positions')) {
            Schema::create('job_positions', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('department');
                $table->text('description');
                $table->json('requirements');
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
        }

        // Create job_applications table if it doesn't exist
        if (!Schema::hasTable('job_applications')) {
            Schema::create('job_applications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('position_id')->constrained('job_positions')->onDelete('cascade');
                $table->string('applicant_name');
                $table->string('applicant_email');
                $table->string('applicant_phone')->nullable();
                $table->json('resume_data');
                $table->text('cover_letter')->nullable();
                $table->json('attachments');
                $table->enum('status', [
                    'applied', 'screening', 'interview_scheduled', 
                    'interviewed', 'offer_made', 'hired', 'rejected'
                ])->default('applied');
                $table->json('interview_notes')->nullable();
                $table->decimal('rating', 3, 2)->nullable();
                $table->timestamps();
                
                $table->index(['position_id', 'status']);
                $table->index(['applicant_email', 'status']);
            });
        }

        // Create announcements table if it doesn't exist
        if (!Schema::hasTable('announcements')) {
            Schema::create('announcements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->string('title');
                $table->text('content');
                $table->enum('type', ['general', 'policy', 'event', 'urgent', 'celebration']);
                $table->enum('target_audience', ['all', 'department', 'role', 'specific_users']);
                $table->json('target_criteria')->nullable();
                $table->boolean('is_pinned')->default(false);
                $table->timestamp('published_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
                
                $table->index(['type', 'published_at']);
                $table->index(['target_audience', 'is_pinned']);
            });
        }

        // Create delivery_routes table if it doesn't exist
        if (!Schema::hasTable('delivery_routes')) {
            Schema::create('delivery_routes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('driver_id')->constrained()->onDelete('cascade');
                $table->date('route_date');
                $table->json('waypoints');
                $table->json('optimized_sequence');
                $table->decimal('total_distance', 10, 2)->nullable();
                $table->integer('estimated_duration')->nullable();
                $table->enum('status', ['planned', 'active', 'completed', 'cancelled'])->default('planned');
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
                
                $table->index(['driver_id', 'route_date']);
                $table->index(['status', 'route_date']);
            });
        }

        // Create vehicle_maintenance table if it doesn't exist
        if (!Schema::hasTable('vehicle_maintenance')) {
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
                $table->json('attachments')->nullable();
                $table->timestamps();
                
                $table->index(['driver_id', 'maintenance_date']);
                $table->index(['type', 'status']);
            });
        }

        // Create database_backups table if it doesn't exist
        if (!Schema::hasTable('database_backups')) {
            Schema::create('database_backups', function (Blueprint $table) {
                $table->id();
                $table->string('backup_name');
                $table->string('database_name');
                $table->enum('type', ['full', 'incremental', 'differential']);
                $table->bigInteger('file_size');
                $table->string('file_path');
                $table->string('checksum')->nullable();
                $table->enum('status', ['in_progress', 'completed', 'failed', 'corrupted']);
                $table->timestamp('started_at');
                $table->timestamp('completed_at')->nullable();
                $table->integer('duration_seconds')->nullable();
                $table->text('error_message')->nullable();
                $table->timestamps();
                
                $table->index(['database_name', 'status']);
                $table->index(['type', 'completed_at']);
            });
        }

        // Create api_errors table if it doesn't exist
        if (!Schema::hasTable('api_errors')) {
            Schema::create('api_errors', function (Blueprint $table) {
                $table->id();
                $table->string('endpoint');
                $table->string('method');
                $table->integer('status_code');
                $table->text('error_message');
                $table->json('request_data')->nullable();
                $table->json('response_data')->nullable();
                $table->string('user_id')->nullable();
                $table->string('ip_address', 45);
                $table->text('user_agent')->nullable();
                $table->decimal('response_time', 8, 3);
                $table->timestamp('occurred_at')->useCurrent();
                
                $table->index(['endpoint', 'status_code']);
                $table->index(['occurred_at', 'status_code']);
                $table->index(['user_id', 'occurred_at']);
            });
        }

        // Create deployment_logs table if it doesn't exist
        if (!Schema::hasTable('deployment_logs')) {
            Schema::create('deployment_logs', function (Blueprint $table) {
                $table->id();
                $table->string('version');
                $table->string('environment');
                $table->enum('status', ['pending', 'in_progress', 'completed', 'failed', 'rolled_back']);
                $table->foreignId('deployed_by')->constrained('users')->onDelete('cascade');
                $table->timestamp('started_at');
                $table->timestamp('completed_at')->nullable();
                $table->json('changes')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                
                $table->index(['environment', 'status']);
                $table->index(['version', 'environment']);
            });
        }

        // Create system_settings table if it doesn't exist
        if (!Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('type')->default('string');
                $table->text('description')->nullable();
                $table->boolean('is_public')->default(false);
                $table->timestamps();
                
                $table->index(['key', 'is_public']);
            });
        }

        // =====================================================
        // CREATE USER_ROLES PIVOT TABLE IF NOT EXISTS
        // =====================================================
        
        if (!Schema::hasTable('user_roles')) {
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
        }

        // =====================================================
        // ADD INDEXES FOR PERFORMANCE
        // =====================================================
        
        // Add indexes to existing tables if they don't exist
        if (Schema::hasTable('orders')) {
            try {
                Schema::table('orders', function (Blueprint $table) {
                    $table->index(['store_id', 'status', 'created_at'], 'orders_store_status_date');
                });
            } catch (Exception $e) {
                // Index might already exist
            }
        }

        if (Schema::hasTable('financial_transactions')) {
            try {
                Schema::table('financial_transactions', function (Blueprint $table) {
                    $table->index(['store_id', 'type', 'status'], 'transactions_store_type_status');
                });
            } catch (Exception $e) {
                // Index might already exist
            }
        }

        if (Schema::hasTable('products')) {
            try {
                Schema::table('products', function (Blueprint $table) {
                    $table->index(['store_id', 'is_active', 'created_at'], 'products_store_active_date');
                });
            } catch (Exception $e) {
                // Index might already exist
            }
        }
    }

    public function down()
    {
        // Remove added columns and tables
        if (Schema::hasTable('drivers')) {
            Schema::table('drivers', function (Blueprint $table) {
                $table->dropColumn(['working_hours', 'last_location', 'last_location_update', 'current_speed', 'current_heading']);
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['status']);
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn(['commission_amount']);
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn(['cost_price', 'low_stock_threshold', 'track_inventory', 'weight', 'dimensions']);
            });
        }

        if (Schema::hasTable('stores')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropColumn(['total_earnings', 'available_balance', 'pending_payout', 'total_orders', 'last_order_at']);
            });
        }

        // Drop new tables
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('deployment_logs');
        Schema::dropIfExists('api_errors');
        Schema::dropIfExists('database_backups');
        Schema::dropIfExists('vehicle_maintenance');
        Schema::dropIfExists('delivery_routes');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('job_positions');
        Schema::dropIfExists('payroll_records');
        Schema::dropIfExists('shifts');
        Schema::dropIfExists('user_roles');
    }
};
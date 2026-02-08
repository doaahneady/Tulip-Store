<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('training_assignments')) {
            Schema::create('training_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained()->onDelete('cascade');
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('category')->nullable();
                $table->enum('status', ['assigned', 'in_progress', 'completed', 'expired'])->default('assigned');
                $table->date('assigned_date')->nullable();
                $table->date('due_date')->nullable();
                $table->date('completed_date')->nullable();
                $table->foreignId('assigned_by')->nullable()->constrained('employees')->nullOnDelete();
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->index(['employee_id', 'status']);
                $table->index(['due_date']);
            });
        }

        if (! Schema::hasTable('open_positions')) {
            Schema::create('open_positions', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('department')->nullable();
                $table->text('description')->nullable();
                $table->json('requirements')->nullable();
                $table->decimal('salary_min', 10, 2)->nullable();
                $table->decimal('salary_max', 10, 2)->nullable();
                $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'intern'])->default('full_time');
                $table->enum('status', ['draft', 'active', 'paused', 'closed'])->default('active');
                $table->foreignId('hiring_manager_id')->nullable()->constrained('employees')->nullOnDelete();
                $table->date('application_deadline')->nullable();
                $table->timestamps();
                $table->index(['status', 'department']);
                $table->index(['hiring_manager_id', 'status']);
            });
        }

        if (! Schema::hasTable('inventory_shrinkage')) {
            Schema::create('inventory_shrinkage', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('quantity_loss');
                $table->string('reason')->nullable();
                $table->text('notes')->nullable();
                $table->foreignId('reported_by')->nullable()->constrained('employees')->nullOnDelete();
                $table->timestamp('reported_at')->useCurrent();
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->index(['product_id', 'reported_at']);
            });
        }

        if (! Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('session_id')->nullable()->index();
                $table->string('status')->default('active');
                $table->timestamps();
                $table->index(['user_id', 'status']);
            });
        }

        if (! Schema::hasTable('cart_items')) {
            Schema::create('cart_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cart_id')->constrained('carts')->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('quantity');
                $table->decimal('unit_price', 10, 2)->nullable();
                $table->decimal('total_price', 10, 2)->nullable();
                $table->json('product_snapshot')->nullable();
                $table->timestamps();
                $table->unique(['cart_id', 'product_id']);
                $table->index(['product_id']);
            });
        }

        if (! Schema::hasTable('favorites')) {
            Schema::create('favorites', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                $table->unique(['user_id', 'product_id']);
                $table->index(['product_id']);
            });
        }

        if (! Schema::hasTable('addresses')) {
            Schema::create('addresses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('label')->nullable();
                $table->string('name')->nullable();
                $table->string('phone')->nullable();
                $table->string('country')->nullable();
                $table->string('state')->nullable();
                $table->string('city')->nullable();
                $table->string('postal_code')->nullable();
                $table->string('street')->nullable();
                $table->text('notes')->nullable();
                $table->boolean('is_default')->default(false);
                $table->timestamps();
                $table->index(['user_id', 'is_default']);
            });
        }

        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->string('payment_method')->nullable();
                $table->string('transaction_id')->nullable()->index();
                $table->decimal('amount', 12, 2);
                $table->string('currency', 3)->default('USD');
                $table->string('status')->default('pending');
                $table->timestamp('paid_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->index(['order_id', 'status']);
            });
        }

        if (! Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('contact_person')->nullable();
                $table->text('address')->nullable();
                $table->string('status')->default('active');
                $table->timestamps();
                $table->index(['status', 'name']);
            });
        }

        if (! Schema::hasTable('discount_codes')) {
            Schema::create('discount_codes', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('type')->default('percentage');
                $table->decimal('value', 10, 2);
                $table->integer('max_uses')->nullable();
                $table->integer('used_count')->default(0);
                $table->timestamp('valid_from')->nullable();
                $table->timestamp('valid_until')->nullable();
                $table->boolean('is_active')->default(true);
                $table->foreignId('store_id')->nullable()->constrained()->nullOnDelete();
                $table->json('conditions')->nullable();
                $table->timestamps();
                $table->index(['is_active', 'valid_from', 'valid_until']);
            });
        }

        if (! Schema::hasTable('refund_requests')) {
            Schema::create('refund_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('status')->default('pending');
                $table->decimal('amount', 12, 2)->nullable();
                $table->string('reason')->nullable();
                $table->foreignId('processed_by')->nullable()->constrained('employees')->nullOnDelete();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
                $table->index(['order_id', 'status']);
                $table->index(['user_id', 'status']);
            });
        }

        if (! Schema::hasTable('inventory_logs')) {
            Schema::create('inventory_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->string('action');
                $table->integer('quantity_change');
                $table->integer('resulting_stock')->nullable();
                $table->string('reason')->nullable();
                $table->foreignId('performed_by')->nullable()->constrained('employees')->nullOnDelete();
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->index(['product_id', 'action']);
                $table->index(['performed_by', 'created_at']);
            });
        }

        if (! Schema::hasTable('search_logs')) {
            Schema::create('search_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('query_text');
                $table->integer('results_count')->default(0);
                $table->boolean('no_results')->default(false);
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->index(['user_id', 'created_at']);
                $table->index(['no_results', 'created_at']);
            });
        }

        if (! Schema::hasTable('delivery_proofs')) {
            Schema::create('delivery_proofs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('delivery_assignment_id')->constrained('delivery_assignments')->onDelete('cascade');
                $table->string('proof_type')->default('photo');
                $table->string('file_url');
                $table->timestamp('captured_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->index(['delivery_assignment_id', 'captured_at']);
            });
        }

        if (! Schema::hasTable('delivery_attempts')) {
            Schema::create('delivery_attempts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('delivery_assignment_id')->constrained('delivery_assignments')->onDelete('cascade');
                $table->integer('attempt_number')->default(1);
                $table->string('status')->default('failed');
                $table->string('reason')->nullable();
                $table->timestamp('attempted_at')->nullable();
                $table->timestamps();
                $table->unique(['delivery_assignment_id', 'attempt_number']);
                $table->index(['status', 'attempted_at']);
            });
        }

        if (! Schema::hasTable('failed_login_attempts')) {
            Schema::create('failed_login_attempts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('email')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->string('reason')->nullable();
                $table->timestamp('attempted_at')->useCurrent();
                $table->timestamps();
                $table->index(['user_id', 'attempted_at']);
                $table->index(['email', 'attempted_at']);
                $table->index(['ip_address', 'attempted_at']);
            });
        }

        if (! Schema::hasTable('system_errors')) {
            Schema::create('system_errors', function (Blueprint $table) {
                $table->id();
                $table->string('error_code')->nullable();
                $table->string('message');
                $table->text('stack_trace')->nullable();
                $table->json('context')->nullable();
                $table->string('severity')->default('error');
                $table->timestamp('occurred_at')->useCurrent();
                $table->boolean('resolved')->default(false);
                $table->timestamp('resolved_at')->nullable();
                $table->foreignId('resolved_by')->nullable()->constrained('employees')->nullOnDelete();
                $table->timestamps();
                $table->index(['severity', 'occurred_at']);
                $table->index(['resolved', 'resolved_at']);
            });
        }

        if (! Schema::hasTable('deployment_history')) {
            Schema::create('deployment_history', function (Blueprint $table) {
                $table->id();
                $table->string('version');
                $table->string('environment');
                $table->string('status')->default('pending');
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

        if (! Schema::hasTable('incidents')) {
            Schema::create('incidents', function (Blueprint $table) {
                $table->id();
                $table->string('type');
                $table->string('severity')->default('medium');
                $table->string('status')->default('open');
                $table->text('description')->nullable();
                $table->foreignId('reported_by')->nullable()->constrained('employees')->nullOnDelete();
                $table->timestamp('reported_at')->useCurrent();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();
                $table->index(['type', 'severity', 'status']);
            });
        }

        if (! Schema::hasTable('incident_reports')) {
            Schema::create('incident_reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('incident_id')->constrained('incidents')->onDelete('cascade');
                $table->foreignId('author_id')->nullable()->constrained('employees')->nullOnDelete();
                $table->text('report');
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->index(['incident_id', 'author_id']);
            });
        }

        if (! Schema::hasTable('incident_media')) {
            Schema::create('incident_media', function (Blueprint $table) {
                $table->id();
                $table->foreignId('incident_id')->constrained('incidents')->onDelete('cascade');
                $table->string('media_type')->default('image');
                $table->string('file_url');
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->index(['incident_id', 'media_type']);
            });
        }

        if (! Schema::hasTable('hr_cases')) {
            Schema::create('hr_cases', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained()->onDelete('cascade');
                $table->string('case_type');
                $table->string('status')->default('open');
                $table->text('notes')->nullable();
                $table->timestamp('opened_at')->useCurrent();
                $table->timestamp('closed_at')->nullable();
                $table->timestamps();
                $table->index(['employee_id', 'status']);
            });
        }

        if (! Schema::hasTable('insurance_claims')) {
            Schema::create('insurance_claims', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('delivery_assignment_id')->nullable()->constrained('delivery_assignments')->nullOnDelete();
                $table->foreignId('driver_id')->nullable()->constrained()->nullOnDelete();
                $table->decimal('claim_amount', 12, 2)->default(0);
                $table->string('status')->default('pending');
                $table->text('description')->nullable();
                $table->timestamp('submitted_at')->useCurrent();
                $table->timestamp('processed_at')->nullable();
                $table->foreignId('processed_by')->nullable()->constrained('employees')->nullOnDelete();
                $table->timestamps();
                $table->index(['status', 'submitted_at']);
                $table->index(['driver_id', 'status']);
            });
        }

        if (! Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_id')->nullable()->constrained()->nullOnDelete();
                $table->string('department')->nullable();
                $table->string('category');
                $table->decimal('amount', 12, 2);
                $table->string('currency', 3)->default('USD');
                $table->string('status')->default('pending');
                $table->timestamp('incurred_at')->useCurrent();
                $table->foreignId('approved_by')->nullable()->constrained('employees')->nullOnDelete();
                $table->timestamp('approved_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->index(['store_id', 'status']);
                $table->index(['department', 'category']);
            });
        }

        if (! Schema::hasTable('bank_transactions')) {
            Schema::create('bank_transactions', function (Blueprint $table) {
                $table->id();
                $table->string('bank_reference')->nullable()->index();
                $table->string('type')->default('transfer');
                $table->decimal('amount', 12, 2);
                $table->string('currency', 3)->default('USD');
                $table->string('status')->default('pending');
                $table->timestamp('occurred_at')->useCurrent();
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->index(['type', 'status']);
                $table->index(['occurred_at']);
            });
        }

        if (! Schema::hasTable('performance_bonuses')) {
            Schema::create('performance_bonuses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained()->onDelete('cascade');
                $table->string('period');
                $table->decimal('amount', 12, 2);
                $table->string('reason')->nullable();
                $table->foreignId('granted_by')->nullable()->constrained('employees')->nullOnDelete();
                $table->timestamp('granted_at')->useCurrent();
                $table->timestamps();
                $table->unique(['employee_id', 'period']);
                $table->index(['granted_at']);
            });
        }

        if (! Schema::hasTable('employee_notes')) {
            Schema::create('employee_notes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained()->onDelete('cascade');
                $table->foreignId('author_id')->nullable()->constrained('employees')->nullOnDelete();
                $table->text('note');
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->index(['employee_id', 'author_id']);
            });
        }

        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (! Schema::hasTable('email_queue')) {
            Schema::create('email_queue', function (Blueprint $table) {
                $table->id();
                $table->string('to');
                $table->string('subject')->nullable();
                $table->text('body');
                $table->string('status')->default('pending');
                $table->integer('attempts')->default(0);
                $table->timestamp('scheduled_at')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->index(['status', 'scheduled_at']);
            });
        }

        if (! Schema::hasTable('delivery_ratings')) {
            Schema::create('delivery_ratings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('driver_id')->constrained()->onDelete('cascade');
                $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->unsignedTinyInteger('rating');
                $table->text('comment')->nullable();
                $table->timestamps();
                $table->index(['driver_id', 'rating', 'created_at']);
                $table->index(['order_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_shrinkage');
        Schema::dropIfExists('open_positions');
        Schema::dropIfExists('training_assignments');
        Schema::dropIfExists('delivery_ratings');
        Schema::dropIfExists('email_queue');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('employee_notes');
        Schema::dropIfExists('performance_bonuses');
        Schema::dropIfExists('bank_transactions');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('insurance_claims');
        Schema::dropIfExists('hr_cases');
        Schema::dropIfExists('incident_media');
        Schema::dropIfExists('incident_reports');
        Schema::dropIfExists('incidents');
        Schema::dropIfExists('deployment_history');
        Schema::dropIfExists('system_errors');
        Schema::dropIfExists('failed_login_attempts');
        Schema::dropIfExists('delivery_attempts');
        Schema::dropIfExists('delivery_proofs');
        Schema::dropIfExists('search_logs');
        Schema::dropIfExists('inventory_logs');
        Schema::dropIfExists('refund_requests');
        Schema::dropIfExists('discount_codes');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};

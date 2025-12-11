<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Employees table
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('employee_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('national_id')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Saudi Arabia');
            
            // Employment details
            $table->string('department');
            $table->string('position');
            $table->string('employment_type')->default('full-time'); // full-time, part-time, contract
            $table->date('hire_date');
            $table->date('contract_end_date')->nullable();
            $table->decimal('salary', 10, 2);
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('iban')->nullable();
            
            // Emergency contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relation')->nullable();
            
            // Status
            $table->enum('status', ['active', 'on_leave', 'suspended', 'terminated'])->default('active');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });

        // Attendance table
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->integer('work_hours')->nullable(); // in minutes
            $table->integer('overtime_hours')->default(0); // in minutes
            $table->enum('status', ['present', 'absent', 'late', 'half_day', 'on_leave'])->default('present');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['employee_id', 'date']);
        });

        // Leave requests table
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->enum('leave_type', ['annual', 'sick', 'emergency', 'unpaid', 'maternity', 'paternity']);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days_count');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });

        // Payroll table
        Schema::create('payroll', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('month'); // e.g., "2025-12"
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('allowances', 10, 2)->default(0);
            $table->decimal('bonuses', 10, 2)->default(0);
            $table->decimal('overtime_pay', 10, 2)->default(0);
            $table->decimal('deductions', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('insurance', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2);
            $table->enum('status', ['draft', 'processed', 'paid'])->default('draft');
            $table->date('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['employee_id', 'month']);
        });

        // Performance reviews table
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->string('review_period'); // e.g., "Q1 2025"
            $table->date('review_date');
            $table->integer('performance_score')->default(0); // 0-100
            $table->integer('attendance_score')->default(0);
            $table->integer('quality_score')->default(0);
            $table->integer('teamwork_score')->default(0);
            $table->integer('overall_rating')->default(0); // 1-5
            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('goals')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
        });

        // Training programs table
        Schema::create('training_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('trainer')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration_hours')->default(0);
            $table->string('location')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->integer('max_participants')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });

        // Training enrollments table
        Schema::create('training_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_program_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['enrolled', 'completed', 'failed', 'withdrawn'])->default('enrolled');
            $table->integer('score')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();
            
            $table->unique(['training_program_id', 'employee_id']);
        });

        // Documents table
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('document_type'); // contract, id_copy, certificate, etc.
            $table->string('document_name');
            $table->string('file_path');
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Add HR fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_hr')->default(false)->after('is_it');
            $table->boolean('is_hr_manager')->default(false)->after('is_hr');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_hr', 'is_hr_manager']);
        });
        
        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('training_enrollments');
        Schema::dropIfExists('training_programs');
        Schema::dropIfExists('performance_reviews');
        Schema::dropIfExists('payroll');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('attendance');
        Schema::dropIfExists('employees');
    }
};

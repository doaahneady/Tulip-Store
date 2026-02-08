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
        Schema::table('employees', function (Blueprint $table) {
            // Enhanced Personal Information
            if (! Schema::hasColumn('employees', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('email');
            }
            if (! Schema::hasColumn('employees', 'bio')) {
                $table->text('bio')->nullable()->after('profile_photo');
            }

            // Professional Information
            if (! Schema::hasColumn('employees', 'employee_id_card')) {
                $table->string('employee_id_card')->nullable()->after('employee_code');
            }
            if (! Schema::hasColumn('employees', 'work_location')) {
                $table->string('work_location')->nullable()->after('department');
            }
            if (! Schema::hasColumn('employees', 'manager_id')) {
                $table->unsignedBigInteger('manager_id')->nullable()->after('position');
            }
            if (! Schema::hasColumn('employees', 'security_level')) {
                $table->enum('security_level', ['1', '2', '3', '4', '5'])->default('1')->after('status');
            }

            // Skills and Qualifications
            if (! Schema::hasColumn('employees', 'skills')) {
                $table->json('skills')->nullable()->after('notes');
            }
            if (! Schema::hasColumn('employees', 'qualifications')) {
                $table->json('qualifications')->nullable()->after('skills');
            }
            if (! Schema::hasColumn('employees', 'certifications')) {
                $table->json('certifications')->nullable()->after('qualifications');
            }
            if (! Schema::hasColumn('employees', 'languages')) {
                $table->json('languages')->nullable()->after('certifications');
            }

            // Work Preferences
            if (! Schema::hasColumn('employees', 'work_schedule')) {
                $table->json('work_schedule')->nullable()->after('employment_type');
            }
            if (! Schema::hasColumn('employees', 'preferred_communication')) {
                $table->enum('preferred_communication', ['email', 'phone', 'whatsapp', 'teams'])->default('email')->after('languages');
            }

            // Financial Information
            if (! Schema::hasColumn('employees', 'approval_limit')) {
                $table->decimal('approval_limit', 15, 2)->default(0)->after('salary');
            }
            if (! Schema::hasColumn('employees', 'commission_rate')) {
                $table->decimal('commission_rate', 5, 2)->default(0)->after('approval_limit');
            }

            // System Access
            if (! Schema::hasColumn('employees', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('remember_token');
            }
            if (! Schema::hasColumn('employees', 'login_count')) {
                $table->integer('login_count')->default(0)->after('last_login_at');
            }
            if (! Schema::hasColumn('employees', 'two_factor_enabled')) {
                $table->boolean('two_factor_enabled')->default(false)->after('login_count');
            }
            if (! Schema::hasColumn('employees', 'ip_restrictions')) {
                $table->json('ip_restrictions')->nullable()->after('two_factor_enabled');
            }

            // Performance Metrics
            if (! Schema::hasColumn('employees', 'performance_score')) {
                $table->decimal('performance_score', 3, 2)->nullable()->after('ip_restrictions');
            }
            if (! Schema::hasColumn('employees', 'last_review_date')) {
                $table->date('last_review_date')->nullable()->after('performance_score');
            }
            if (! Schema::hasColumn('employees', 'next_review_date')) {
                $table->date('next_review_date')->nullable()->after('last_review_date');
            }

            // Additional Role Flags
            if (! Schema::hasColumn('employees', 'is_manager')) {
                $table->boolean('is_manager')->default(false)->after('is_trader');
            }
            if (! Schema::hasColumn('employees', 'is_team_lead')) {
                $table->boolean('is_team_lead')->default(false)->after('is_manager');
            }
            if (! Schema::hasColumn('employees', 'can_approve_expenses')) {
                $table->boolean('can_approve_expenses')->default(false)->after('is_team_lead');
            }
            if (! Schema::hasColumn('employees', 'can_manage_inventory')) {
                $table->boolean('can_manage_inventory')->default(false)->after('can_approve_expenses');
            }

            // Foreign key constraint for manager
            if (Schema::hasColumn('employees', 'manager_id')) {
                $table->foreign('manager_id')->references('id')->on('employees')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Drop foreign key first
            if (Schema::hasColumn('employees', 'manager_id')) {
                $table->dropForeign(['manager_id']);
            }

            // Drop all added columns
            $columnsToRemove = [
                'profile_photo', 'bio', 'employee_id_card', 'work_location', 'manager_id',
                'security_level', 'skills', 'qualifications', 'certifications', 'languages',
                'work_schedule', 'preferred_communication', 'approval_limit', 'commission_rate',
                'last_login_at', 'login_count', 'two_factor_enabled', 'ip_restrictions',
                'performance_score', 'last_review_date', 'next_review_date',
                'is_manager', 'is_team_lead', 'can_approve_expenses', 'can_manage_inventory',
            ];

            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('employees', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

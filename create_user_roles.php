<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
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
        echo "✓ user_roles table created successfully\n";
    } else {
        echo "✓ user_roles table already exists\n";
    }

    // Also create other missing tables
    $tables = [
        'shifts' => function (Blueprint $table) {
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
        },
        'payroll_records' => function (Blueprint $table) {
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
        }
    ];

    foreach ($tables as $tableName => $callback) {
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, $callback);
            echo "✓ {$tableName} table created successfully\n";
        } else {
            echo "✓ {$tableName} table already exists\n";
        }
    }

    echo "\nAll required tables are now available!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_dashboard_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('dashboard_key', 50);
            $table->timestamps();

            $table->unique(['employee_id', 'dashboard_key'], 'uniq_employee_dashboard');
            $table->index(['dashboard_key'], 'idx_employee_dashboard_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_dashboard_permissions');
    }
};

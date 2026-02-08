<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administrative_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('category', 50);
            $table->decimal('amount', 12, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('details')->nullable();
            $table->string('status', 20)->default('pending');
            $table->foreignId('decided_by_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('decided_by_role', 30)->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'category']);
            $table->index(['requester_employee_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administrative_approvals');
    }
};

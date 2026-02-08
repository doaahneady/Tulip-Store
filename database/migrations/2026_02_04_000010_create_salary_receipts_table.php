<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_record_id')->nullable();
            $table->unsignedBigInteger('financial_transaction_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('pay_period')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('currency', 10)->default('USD');
            $table->date('paid_date')->nullable();
            $table->string('signed_name')->nullable();
            $table->longText('signature_data')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->unsignedBigInteger('created_by_employee_id')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'pay_period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_receipts');
    }
};

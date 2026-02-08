<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('financial_transactions')) {
            return;
        }

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE financial_transactions MODIFY COLUMN type ENUM('payment','order_payment','commission','payout','refund','fee','adjustment','payroll','salary_payment','expense')");
    }

    public function down(): void
    {
        if (! Schema::hasTable('financial_transactions')) {
            return;
        }

        if (DB::getDriverName() !== 'mysql') {
            return;
        }
    }
};

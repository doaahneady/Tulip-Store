<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modify the status enum to include 'approved'
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE financial_transactions MODIFY COLUMN status ENUM('pending', 'completed', 'failed', 'cancelled', 'approved') DEFAULT 'completed'");
        } else {
            if (Schema::hasTable('financial_transactions')) {
                Schema::table('financial_transactions', function ($table) {
                    // No-op for sqlite/text-based columns; keep compatibility without failing
                });
            }
        }
    }

    public function down(): void
    {
        // Revert to original enum values
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE financial_transactions MODIFY COLUMN status ENUM('pending', 'completed', 'failed', 'cancelled') DEFAULT 'completed'");
        } else {
            if (Schema::hasTable('financial_transactions')) {
                Schema::table('financial_transactions', function ($table) {
                    // No-op for sqlite/text-based columns
                });
            }
        }
    }
};

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

        DB::statement("ALTER TABLE financial_transactions MODIFY COLUMN status ENUM('pending','pending_approval','approved','rejected','processing','completed','failed','cancelled') DEFAULT 'completed'");
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modify the status enum to include 'approved'
        DB::statement("ALTER TABLE financial_transactions MODIFY COLUMN status ENUM('pending', 'completed', 'failed', 'cancelled', 'approved') DEFAULT 'completed'");
    }

    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE financial_transactions MODIFY COLUMN status ENUM('pending', 'completed', 'failed', 'cancelled') DEFAULT 'completed'");
    }
};

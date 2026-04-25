<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all users with NULL or empty currency to SYP
        DB::table('users')
            ->whereNull('currency')
            ->orWhere('currency', '')
            ->update(['currency' => 'SYP']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally revert to USD if needed
        DB::table('users')
            ->where('currency', 'SYP')
            ->update(['currency' => 'USD']);
    }
};

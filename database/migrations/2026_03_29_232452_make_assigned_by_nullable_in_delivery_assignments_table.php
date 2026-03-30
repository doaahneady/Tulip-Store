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
        Schema::table('delivery_assignments', function (Blueprint $table) {
            // Make assigned_by nullable to handle cases where we can't determine the user
            $table->unsignedBigInteger('assigned_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_assignments', function (Blueprint $table) {
            // Revert to non-nullable (but this might fail if there are null values)
            $table->unsignedBigInteger('assigned_by')->nullable(false)->change();
        });
    }
};

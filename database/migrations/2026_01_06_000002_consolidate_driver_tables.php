<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Consolidate driver tables and cleanup redundancies.
     */
    public function up(): void
    {
        // 1. Drop redundant tables that have better replacements
        Schema::dropIfExists('driver_location_history'); // Replaced by driver_locations
        Schema::dropIfExists('driver_performance');      // Replaced by driver_performance_scores

        // 2. Update delivery_assignments to reference 'drivers' instead of 'delivery_drivers'
        if (Schema::hasTable('delivery_assignments')) {

            // Try to drop Foreign Key
            try {
                Schema::table('delivery_assignments', function (Blueprint $table) {
                    $table->dropForeign(['driver_id']);
                });
            } catch (\Exception $e) {
                // Ignore if not found
            }

            // Try to drop Index
            try {
                Schema::table('delivery_assignments', function (Blueprint $table) {
                    $table->dropIndex(['driver_id']);
                });
            } catch (\Exception $e) {
                // Ignore if not found
            }

            // Now re-add the foreign key to 'drivers'
            try {
                Schema::table('delivery_assignments', function (Blueprint $table) {
                    // Ensure driver_id column exists (it should)
                    if (Schema::hasColumn('delivery_assignments', 'driver_id')) {
                        $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
                        $table->index('driver_id');
                    }
                });
            } catch (\Exception $e) {
                // Ignore if already exists or other error
            }
        }

        // 3. Drop delivery_drivers as it is replaced by drivers
        Schema::dropIfExists('delivery_drivers');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We cannot easily restore data, but we can recreate tables structure if needed.
    }
};

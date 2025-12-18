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
        if (Schema::hasTable('system_alerts')) {
            Schema::table('system_alerts', function (Blueprint $table) {
                // Add status column if it doesn't exist
                if (!Schema::hasColumn('system_alerts', 'status')) {
                    $table->enum('status', ['active', 'resolved', 'dismissed'])->default('active')->after('message');
                }
                
                // Add severity column if it doesn't exist (alias for priority)
                if (!Schema::hasColumn('system_alerts', 'severity')) {
                    $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium')->after('status');
                }
            });
            
            // Copy data from priority to severity if both exist
            if (Schema::hasColumn('system_alerts', 'priority') && Schema::hasColumn('system_alerts', 'severity')) {
                DB::statement('UPDATE system_alerts SET severity = priority WHERE severity = "medium"');
            }
            
            // Set default status based on is_resolved
            if (Schema::hasColumn('system_alerts', 'is_resolved') && Schema::hasColumn('system_alerts', 'status')) {
                DB::statement('UPDATE system_alerts SET status = CASE WHEN is_resolved = 1 THEN "resolved" ELSE "active" END WHERE status = "active"');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('system_alerts')) {
            Schema::table('system_alerts', function (Blueprint $table) {
                if (Schema::hasColumn('system_alerts', 'status')) {
                    $table->dropColumn('status');
                }
                if (Schema::hasColumn('system_alerts', 'severity')) {
                    $table->dropColumn('severity');
                }
            });
        }
    }
};
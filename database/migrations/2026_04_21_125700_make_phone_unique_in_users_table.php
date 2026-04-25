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
        Schema::table('users', function (Blueprint $table) {
            // Make phone unique if it exists
            if (Schema::hasColumn('users', 'phone')) {
                // First, remove any duplicate phone numbers (keep the first one)
                \DB::statement('
                    UPDATE users u1
                    LEFT JOIN (
                        SELECT MIN(id) as id, phone
                        FROM users
                        WHERE phone IS NOT NULL AND phone != ""
                        GROUP BY phone
                    ) u2 ON u1.phone = u2.phone AND u1.id = u2.id
                    SET u1.phone = NULL
                    WHERE u1.phone IS NOT NULL 
                    AND u1.phone != ""
                    AND u2.id IS NULL
                ');
                
                // Now make it unique
                $table->string('phone')->nullable()->unique()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropUnique(['phone']);
            }
        });
    }
};

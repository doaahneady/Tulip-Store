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
            if (! Schema::hasColumn('users', 'is_it_super')) {
                $table->boolean('is_it_super')->default(false)->after('is_admin');
            }
            if (! Schema::hasColumn('users', 'is_it')) {
                $table->boolean('is_it')->default(false)->after('is_it_super');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_it_super', 'is_it']);
        });
    }
};

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
            if (!Schema::hasColumn('users', 'is_hr')) {
                $table->boolean('is_hr')->default(false)->after('is_admin');
            }
            if (!Schema::hasColumn('users', 'is_cs')) {
                $table->boolean('is_cs')->default(false)->after('is_hr');
            }
            if (!Schema::hasColumn('users', 'is_finance')) {
                $table->boolean('is_finance')->default(false)->after('is_cs');
            }
            if (!Schema::hasColumn('users', 'is_accountant')) {
                $table->boolean('is_accountant')->default(false)->after('is_finance');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_hr', 'is_cs', 'is_finance', 'is_accountant']);
        });
    }
};

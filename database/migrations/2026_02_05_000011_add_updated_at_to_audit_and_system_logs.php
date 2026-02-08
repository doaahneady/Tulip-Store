<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('audit_logs') && ! Schema::hasColumn('audit_logs', 'updated_at')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            });
        }

        if (Schema::hasTable('system_logs') && ! Schema::hasColumn('system_logs', 'updated_at')) {
            Schema::table('system_logs', function (Blueprint $table) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('audit_logs') && Schema::hasColumn('audit_logs', 'updated_at')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->dropColumn('updated_at');
            });
        }

        if (Schema::hasTable('system_logs') && Schema::hasColumn('system_logs', 'updated_at')) {
            Schema::table('system_logs', function (Blueprint $table) {
                $table->dropColumn('updated_at');
            });
        }
    }
};

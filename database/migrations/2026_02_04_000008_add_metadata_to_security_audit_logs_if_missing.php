<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('security_audit_logs')) {
            return;
        }

        Schema::table('security_audit_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('security_audit_logs', 'metadata')) {
                $table->json('metadata')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('security_audit_logs')) {
            return;
        }

        Schema::table('security_audit_logs', function (Blueprint $table) {
            if (Schema::hasColumn('security_audit_logs', 'metadata')) {
                $table->dropColumn('metadata');
            }
        });
    }
};

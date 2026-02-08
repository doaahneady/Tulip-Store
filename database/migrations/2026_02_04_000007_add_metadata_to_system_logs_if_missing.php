<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('system_logs')) {
            return;
        }

        Schema::table('system_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('system_logs', 'metadata')) {
                $table->json('metadata')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('system_logs')) {
            return;
        }

        Schema::table('system_logs', function (Blueprint $table) {
            if (Schema::hasColumn('system_logs', 'metadata')) {
                $table->dropColumn('metadata');
            }
        });
    }
};

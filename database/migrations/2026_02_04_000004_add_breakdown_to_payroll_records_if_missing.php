<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payroll_records')) {
            return;
        }

        Schema::table('payroll_records', function (Blueprint $table) {
            if (! Schema::hasColumn('payroll_records', 'breakdown')) {
                $table->json('breakdown')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('payroll_records')) {
            return;
        }

        Schema::table('payroll_records', function (Blueprint $table) {
            if (Schema::hasColumn('payroll_records', 'breakdown')) {
                $table->dropColumn('breakdown');
            }
        });
    }
};

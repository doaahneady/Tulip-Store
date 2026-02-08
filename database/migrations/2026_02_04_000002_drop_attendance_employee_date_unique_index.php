<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('attendance')) {
            return;
        }

        try {
            Schema::table('attendance', function (Blueprint $table) {
                $table->dropUnique('attendance_employee_id_date_unique');
            });
        } catch (\Throwable $e) {
        }

        try {
            Schema::table('attendance', function (Blueprint $table) {
                $table->dropUnique(['employee_id', 'date']);
            });
        } catch (\Throwable $e) {
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('attendance')) {
            return;
        }

        try {
            Schema::table('attendance', function (Blueprint $table) {
                $table->unique(['employee_id', 'date'], 'attendance_employee_id_date_unique');
            });
        } catch (\Throwable $e) {
        }
    }
};

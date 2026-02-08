<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('attendance')) {
            return;
        }

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        try {
            DB::statement('ALTER TABLE attendance ADD INDEX idx_attendance_employee_id (employee_id)');
        } catch (\Throwable $e) {
        }

        try {
            DB::statement('ALTER TABLE attendance DROP INDEX attendance_employee_id_date_unique');
        } catch (\Throwable $e) {
        }

        try {
            DB::statement('ALTER TABLE attendance ADD INDEX idx_attendance_employee_date (employee_id, `date`)');
        } catch (\Throwable $e) {
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('attendance')) {
            return;
        }

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        try {
            DB::statement('ALTER TABLE attendance DROP INDEX idx_attendance_employee_date');
        } catch (\Throwable $e) {
        }

        try {
            DB::statement('ALTER TABLE attendance DROP INDEX idx_attendance_employee_id');
        } catch (\Throwable $e) {
        }

        try {
            DB::statement('ALTER TABLE attendance ADD UNIQUE attendance_employee_id_date_unique (employee_id, `date`)');
        } catch (\Throwable $e) {
        }
    }
};

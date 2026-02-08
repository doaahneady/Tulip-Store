<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('drivers')) {
            return;
        }

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (! Schema::hasColumn('drivers', 'availability')) {
            return;
        }

        DB::statement("ALTER TABLE drivers MODIFY COLUMN availability ENUM('available','busy','offline','on_break') NOT NULL DEFAULT 'offline'");
    }

    public function down(): void
    {
        if (! Schema::hasTable('drivers')) {
            return;
        }

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (! Schema::hasColumn('drivers', 'availability')) {
            return;
        }

        DB::statement("ALTER TABLE drivers MODIFY COLUMN availability ENUM('available','busy','offline') NOT NULL DEFAULT 'offline'");
    }
};

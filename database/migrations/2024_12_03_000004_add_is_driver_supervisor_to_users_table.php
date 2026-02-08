<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'is_driver_supervisor')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_driver_supervisor')->default(false);
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_driver_supervisor');
        });
    }
};

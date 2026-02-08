<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'locked_at')) {
                    $table->timestamp('locked_at')->nullable()->after('remember_token');
                }
                if (! Schema::hasColumn('users', 'locked_until')) {
                    $table->timestamp('locked_until')->nullable()->after('locked_at');
                }
                if (! Schema::hasColumn('users', 'lock_reason')) {
                    $table->string('lock_reason')->nullable()->after('locked_until');
                }
                if (! Schema::hasColumn('users', 'login_failures')) {
                    $table->unsignedInteger('login_failures')->default(0)->after('lock_reason');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'login_failures')) {
                    $table->dropColumn('login_failures');
                }
                if (Schema::hasColumn('users', 'lock_reason')) {
                    $table->dropColumn('lock_reason');
                }
                if (Schema::hasColumn('users', 'locked_until')) {
                    $table->dropColumn('locked_until');
                }
                if (Schema::hasColumn('users', 'locked_at')) {
                    $table->dropColumn('locked_at');
                }
            });
        }
    }
};

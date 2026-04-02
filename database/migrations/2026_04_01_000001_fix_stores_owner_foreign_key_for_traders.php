<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('stores')) {
            return;
        }

        if (! Schema::hasColumn('stores', 'owner_id')) {
            return;
        }

        if (! Schema::hasColumn('stores', 'user_id')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('owner_id');
                $table->index(['user_id']);
            });
        }

        if (Schema::hasColumn('stores', 'owner_id')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->unsignedBigInteger('owner_id')->nullable()->change();
            });
        }

        try {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropForeign('stores_owner_id_foreign');
            });
        } catch (\Throwable $e) {
        }

        if (Schema::hasColumn('stores', 'user_id') && Schema::hasTable('users')) {
            try {
                Schema::table('stores', function (Blueprint $table) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                });
            } catch (\Throwable $e) {
            }
        }

        if (Schema::hasTable('traders')) {
            try {
                DB::statement('UPDATE stores s LEFT JOIN traders t ON t.id = s.owner_id SET s.user_id = IFNULL(s.user_id, s.owner_id), s.owner_id = NULL WHERE s.owner_id IS NOT NULL AND t.id IS NULL');
            } catch (\Throwable $e) {
            }

            try {
                Schema::table('stores', function (Blueprint $table) {
                    $table->foreign('owner_id')->references('id')->on('traders')->onDelete('cascade');
                });
            } catch (\Throwable $e) {
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('stores')) {
            return;
        }

        try {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropForeign('stores_owner_id_foreign');
            });
        } catch (\Throwable $e) {
        }

        try {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropForeign('stores_user_id_foreign');
            });
        } catch (\Throwable $e) {
        }
    }
};


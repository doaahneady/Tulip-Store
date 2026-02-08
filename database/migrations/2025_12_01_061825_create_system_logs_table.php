<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('system_logs')) {
            Schema::table('system_logs', function (Blueprint $table) {
                if (! Schema::hasColumn('system_logs', 'action')) {
                    $table->string('action')->nullable();
                }
                if (! Schema::hasColumn('system_logs', 'user_agent')) {
                    $table->text('user_agent')->nullable();
                }
                if (! Schema::hasColumn('system_logs', 'metadata')) {
                    $table->json('metadata')->nullable();
                }
                if (! Schema::hasColumn('system_logs', 'user')) {
                    $table->string('user')->nullable();
                }
            });
        } else {
            Schema::create('system_logs', function (Blueprint $table) {
                $table->id();
                $table->enum('level', ['info', 'warning', 'error', 'critical'])->default('info');
                $table->string('action')->nullable();
                $table->text('message');
                $table->string('user')->nullable();
                $table->string('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index('level');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safety check
    }
};

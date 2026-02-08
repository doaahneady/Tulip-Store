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
        Schema::create('scheduled_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('command')->nullable();
            $table->string('schedule'); // e.g., "daily", "hourly", "weekly"
            $table->string('schedule_time')->nullable(); // e.g., "02:00"
            $table->enum('status', ['success', 'failed', 'running', 'pending'])->default('pending');
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->integer('run_count')->default(0);
            $table->integer('failure_count')->default(0);
            $table->text('last_output')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();

            $table->index('status');
            $table->index('is_enabled');
            $table->index('next_run_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_tasks');
    }
};

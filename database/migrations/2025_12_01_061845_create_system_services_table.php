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
        Schema::create('system_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('display_name');
            $table->enum('status', ['running', 'stopped', 'error'])->default('running');
            $table->string('uptime')->nullable();
            $table->string('cpu_usage')->nullable();
            $table->string('memory_usage')->nullable();
            $table->integer('port')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            $table->unique('name');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_services');
    }
};

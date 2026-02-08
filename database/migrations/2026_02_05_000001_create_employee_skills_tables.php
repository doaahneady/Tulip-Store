<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('skills')) {
            Schema::create('skills', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->enum('type', ['course', 'strength'])->default('strength');
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['type', 'is_active']);
            });
        }

        if (! Schema::hasTable('employee_skill')) {
            Schema::create('employee_skill', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained()->onDelete('cascade');
                $table->foreignId('skill_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                $table->unique(['employee_id', 'skill_id']);
                $table->index(['skill_id', 'employee_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_skill');
        Schema::dropIfExists('skills');
    }
};

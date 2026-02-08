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
        Schema::create('slow_queries', function (Blueprint $table) {
            $table->id();
            $table->text('query');
            $table->decimal('execution_time', 10, 3); // in seconds
            $table->integer('call_count')->default(1);
            $table->enum('severity', ['low', 'medium', 'high'])->default('medium');
            $table->string('database')->nullable();
            $table->string('table_name')->nullable();
            $table->boolean('is_optimized')->default(false);
            $table->timestamp('optimized_at')->nullable();
            $table->text('optimization_notes')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();

            $table->index('severity');
            $table->index('is_optimized');
            $table->index('execution_time');
            $table->index('last_seen_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slow_queries');
    }
};

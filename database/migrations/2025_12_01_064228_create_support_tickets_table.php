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
        if (! Schema::hasTable('support_tickets')) {
            Schema::create('support_tickets', function (Blueprint $table) {
                $table->id();
                $table->string('ticket_number')->unique();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
                $table->string('subject');
                $table->text('description');
                $table->enum('category', ['order', 'product', 'payment', 'shipping', 'technical', 'other'])->default('other');
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
                $table->enum('status', ['open', 'in_progress', 'waiting_customer', 'resolved', 'closed'])->default('open');
                $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
                $table->timestamp('first_response_at')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamp('closed_at')->nullable();
                $table->integer('satisfaction_rating')->nullable();
                $table->text('satisfaction_comment')->nullable();
                $table->timestamps();

                $table->index('ticket_number');
                $table->index('user_id');
                $table->index('assigned_to');
                $table->index('status');
                $table->index('priority');
                $table->index('category');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};

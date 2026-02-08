<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Cleanup duplicate/legacy tables that are not referenced by the current models.
     */
    public function up(): void
    {
        // Drop legacy ticket_replies if present (model uses support_ticket_replies)
        if (Schema::hasTable('ticket_replies')) {
            Schema::drop('ticket_replies');
        }

        // Align RBAC pivot to permission_role
        if (Schema::hasTable('role_permissions')) {
            Schema::drop('role_permissions');
        }

        if (! Schema::hasTable('permission_role')) {
            Schema::create('permission_role', function (Blueprint $table) {
                $table->foreignId('permission_id')->constrained()->onDelete('cascade');
                $table->foreignId('role_id')->constrained()->onDelete('cascade');
                $table->primary(['permission_id', 'role_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate role_permissions if needed (no timestamps to avoid ambiguity)
        if (! Schema::hasTable('role_permissions')) {
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('permission_id');
                $table->timestamps();

                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
                $table->unique(['role_id', 'permission_id']);
            });
        }

        // Restore ticket_replies with minimal structure if needed
        if (! Schema::hasTable('ticket_replies')) {
            Schema::create('ticket_replies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ticket_id')->constrained('support_tickets')->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('message');
                $table->boolean('is_internal_note')->default(false);
                $table->json('attachments')->nullable();
                $table->timestamps();
            });
        }
    }
};

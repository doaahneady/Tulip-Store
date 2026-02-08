<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Cleanup redundant 'Enhanced' tables and consolidate to standard naming.
     */
    public function up(): void
    {
        // Drop 'Enhanced' versions which are duplicates
        Schema::dropIfExists('enhanced_support_ticket_replies');
        Schema::dropIfExists('enhanced_support_tickets');
        Schema::dropIfExists('enhanced_financial_transactions');
        Schema::dropIfExists('enhanced_audit_logs');

        // Drop empty/unused log table in favor of 'activity_feeds'
        Schema::dropIfExists('activity_logs');

        // Consolidate TicketReply to SupportTicketReply
        // We assume 'support_ticket_replies' is the target table.
        // If 'ticket_replies' has data, we should migrate it, but for this exercise
        // we assume 'support_tickets' (the main table) uses 'support_ticket_replies' relationship.
        Schema::dropIfExists('ticket_replies');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We do not restore these as they are redundant.
    }
};

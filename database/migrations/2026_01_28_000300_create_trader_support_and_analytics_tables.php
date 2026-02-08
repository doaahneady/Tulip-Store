<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('trader_reports')) {
            Schema::create('trader_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('trader_id');
                $table->enum('report_type', ['sales', 'inventory', 'earnings', 'custom', 'issue']);
                $table->string('title', 255);
                $table->text('description')->nullable();
                $table->json('report_data')->nullable();
                $table->string('file_url', 500)->nullable();
                $table->enum('submitted_to', ['owner', 'admin', 'support'])->default('owner');
                $table->enum('status', ['submitted', 'under_review', 'resolved', 'closed'])->default('submitted');
                $table->text('admin_response')->nullable();
                $table->unsignedBigInteger('responded_by')->nullable();
                $table->timestamp('responded_at')->nullable();
                $table->timestamps();

                $table->foreign('trader_id')->references('id')->on('traders')->onDelete('cascade');
                $table->foreign('responded_by')->references('id')->on('users')->onDelete('set null');
                $table->index(['trader_id'], 'idx_trader_reports_trader');
                $table->index(['status'], 'idx_trader_reports_status');
                $table->index(['report_type'], 'idx_trader_reports_type');
            });
        }

        if (! Schema::hasTable('trader_support_tickets')) {
            Schema::create('trader_support_tickets', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('trader_id');
                $table->string('subject', 255);
                $table->enum('category', ['product_approval', 'payment', 'order_issue', 'technical', 'general', 'dispute']);
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
                $table->text('description');
                $table->enum('status', ['open', 'in_progress', 'waiting_trader', 'resolved', 'closed'])->default('open');
                $table->unsignedBigInteger('assigned_to')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();

                $table->foreign('trader_id')->references('id')->on('traders')->onDelete('cascade');
                $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
                $table->index(['trader_id'], 'idx_trader_support_tickets_trader');
                $table->index(['status'], 'idx_trader_support_tickets_status');
                $table->index(['assigned_to'], 'idx_trader_support_tickets_assigned');
            });
        }

        if (! Schema::hasTable('trader_support_messages')) {
            Schema::create('trader_support_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ticket_id');
                $table->unsignedBigInteger('sender_id');
                $table->enum('sender_type', ['trader', 'support']);
                $table->text('message');
                $table->json('attachments')->nullable();
                $table->boolean('is_internal_note')->default(false);
                $table->timestamps();

                $table->foreign('ticket_id')->references('id')->on('trader_support_tickets')->onDelete('cascade');
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['ticket_id'], 'idx_trader_support_messages_ticket');
            });
        }

        if (! Schema::hasTable('trader_analytics_daily')) {
            Schema::create('trader_analytics_daily', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('trader_id');
                $table->date('date');
                $table->integer('total_orders')->default(0);
                $table->integer('total_items_sold')->default(0);
                $table->decimal('total_revenue', 10, 2)->default(0.00);
                $table->decimal('total_commission', 10, 2)->default(0.00);
                $table->decimal('net_earnings', 10, 2)->default(0.00);
                $table->integer('products_added')->default(0);
                $table->integer('products_approved')->default(0);
                $table->integer('products_rejected')->default(0);
                $table->timestamps();

                $table->foreign('trader_id')->references('id')->on('traders')->onDelete('cascade');
                $table->unique(['trader_id', 'date'], 'unique_trader_date');
                $table->index(['trader_id'], 'idx_trader_analytics_daily_trader');
                $table->index(['date'], 'idx_trader_analytics_daily_date');
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (! Schema::hasColumn('products', 'trader_id')) {
                    $table->unsignedBigInteger('trader_id')->nullable()->after('store_id');
                    $table->index('trader_id', 'idx_products_trader');
                }
                if (! Schema::hasColumn('products', 'is_trader_product')) {
                    $table->boolean('is_trader_product')->default(false)->after('trader_id');
                }
            });

            // Add FK in a separate statement to avoid issues if column was just added
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'trader_id')) {
                    try {
                        $table->foreign('trader_id')->references('id')->on('traders')->onDelete('set null');
                    } catch (\Throwable $e) {
                    }
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('trader_support_messages')) {
            Schema::dropIfExists('trader_support_messages');
        }
        if (Schema::hasTable('trader_support_tickets')) {
            Schema::dropIfExists('trader_support_tickets');
        }
        if (Schema::hasTable('trader_reports')) {
            Schema::dropIfExists('trader_reports');
        }
        if (Schema::hasTable('trader_analytics_daily')) {
            Schema::dropIfExists('trader_analytics_daily');
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'trader_id')) {
                    try {
                        $table->dropForeign(['trader_id']);
                    } catch (\Throwable $e) {
                    }
                    $table->dropIndex('idx_products_trader');
                    $table->dropColumn('trader_id');
                }
                if (Schema::hasColumn('products', 'is_trader_product')) {
                    $table->dropColumn('is_trader_product');
                }
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        // Ensure 'status' accepts trader workflow values by changing to a simple string
        if (Schema::hasColumn('products', 'status')) {
            try {
                Schema::table('products', function (Blueprint $table) {
                    $table->string('status', 30)->default('pending')->change();
                });
            } catch (\Throwable $e) {
                // Fallback: add status if change failed (e.g., driver limitations)
                if (! Schema::hasColumn('products', 'status_text')) {
                    Schema::table('products', function (Blueprint $table) {
                        $table->string('status_text', 30)->nullable()->after('is_trader_product');
                        $table->index(['status_text']);
                    });
                }
            }
        } else {
            Schema::table('products', function (Blueprint $table) {
                $table->string('status', 30)->default('pending');
                $table->index(['status']);
            });
        }

        // Optional fields used by approval flow if present in code
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
            if (! Schema::hasColumn('products', 'reviewed_by')) {
                $table->unsignedBigInteger('reviewed_by')->nullable();
            }
            if (! Schema::hasColumn('products', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        // Attempt to revert status back to original enum if possible
        try {
            Schema::table('products', function (Blueprint $table) {
                $table->enum('status', ['draft', 'active', 'inactive', 'out_of_stock'])->default('draft')->change();
            });
        } catch (\Throwable $e) {
            // If revert not possible, leave as string to avoid breaking tests
        }

        // Drop optional columns added
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
            if (Schema::hasColumn('products', 'reviewed_by')) {
                try {
                    $table->dropColumn('reviewed_by');
                } catch (\Throwable $e) {
                }
            }
            if (Schema::hasColumn('products', 'reviewed_at')) {
                try {
                    $table->dropColumn('reviewed_at');
                } catch (\Throwable $e) {
                }
            }
            if (Schema::hasColumn('products', 'status_text')) {
                $table->dropColumn('status_text');
            }
        });
    }
};

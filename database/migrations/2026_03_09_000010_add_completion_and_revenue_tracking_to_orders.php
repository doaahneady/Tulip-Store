<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (! Schema::hasColumn('orders', 'is_completed')) {
                    $table->boolean('is_completed')->default(false)->after('status');
                }
                if (! Schema::hasColumn('orders', 'completed_at')) {
                    $table->timestamp('completed_at')->nullable()->after('is_completed');
                }
                if (! Schema::hasColumn('orders', 'revenue_recognized_at')) {
                    $table->timestamp('revenue_recognized_at')->nullable()->after('completed_at');
                }
            });
        }

        if (! Schema::hasTable('order_revenue_records')) {
            Schema::create('order_revenue_records', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id')->unique();
                $table->unsignedBigInteger('financial_transaction_id')->nullable();
                $table->decimal('amount', 15, 2);
                $table->string('currency', 3)->default('USD');
                $table->timestamp('recognized_at')->useCurrent();
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('orders') && DB::getDriverName() === 'mysql') {
            try {
                DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','confirmed','processing','ready','shipped','out_for_delivery','delivered','done','failed','cancelled','refunded','returned') DEFAULT 'pending'");
            } catch (\Throwable $e) {
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                foreach (['is_completed', 'completed_at', 'revenue_recognized_at'] as $col) {
                    if (Schema::hasColumn('orders', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
        Schema::dropIfExists('order_revenue_records');
    }
};


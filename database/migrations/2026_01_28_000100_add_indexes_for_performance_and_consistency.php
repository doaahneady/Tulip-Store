<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'status')) {
                    $this->addIndexIfMissing($table, 'orders', 'status', 'orders_status_index');
                }
                if (Schema::hasColumn('orders', 'payment_status')) {
                    $this->addIndexIfMissing($table, 'orders', 'payment_status', 'orders_payment_status_index');
                }
                if (Schema::hasColumn('orders', 'user_id')) {
                    $this->addIndexIfMissing($table, 'orders', 'user_id', 'orders_user_id_index');
                }
                if (Schema::hasColumn('orders', 'store_id')) {
                    $this->addIndexIfMissing($table, 'orders', 'store_id', 'orders_store_id_index');
                }
                if (Schema::hasColumn('orders', 'created_at')) {
                    $this->addIndexIfMissing($table, 'orders', 'created_at', 'orders_created_at_index');
                }
            });
        }

        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                if (Schema::hasColumn('order_items', 'order_id')) {
                    $this->addIndexIfMissing($table, 'order_items', 'order_id', 'order_items_order_id_index');
                }
                if (Schema::hasColumn('order_items', 'product_id')) {
                    $this->addIndexIfMissing($table, 'order_items', 'product_id', 'order_items_product_id_index');
                }
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'is_active')) {
                    $this->addIndexIfMissing($table, 'products', 'is_active', 'products_is_active_index');
                }
                if (Schema::hasColumn('products', 'stock_quantity')) {
                    $this->addIndexIfMissing($table, 'products', 'stock_quantity', 'products_stock_quantity_index');
                }
            });
        }

        if (Schema::hasTable('financial_transactions')) {
            Schema::table('financial_transactions', function (Blueprint $table) {
                if (Schema::hasColumn('financial_transactions', 'order_id')) {
                    $this->addIndexIfMissing($table, 'financial_transactions', 'order_id', 'financial_transactions_order_id_index');
                }
                if (Schema::hasColumn('financial_transactions', 'user_id')) {
                    $this->addIndexIfMissing($table, 'financial_transactions', 'user_id', 'financial_transactions_user_id_index');
                }
                if (Schema::hasColumn('financial_transactions', 'store_id')) {
                    $this->addIndexIfMissing($table, 'financial_transactions', 'store_id', 'financial_transactions_store_id_index');
                }
                if (Schema::hasColumn('financial_transactions', 'type')) {
                    $this->addIndexIfMissing($table, 'financial_transactions', 'type', 'financial_transactions_type_index');
                }
                if (Schema::hasColumn('financial_transactions', 'status')) {
                    $this->addIndexIfMissing($table, 'financial_transactions', 'status', 'financial_transactions_status_index');
                }
                if (Schema::hasColumn('financial_transactions', 'created_at')) {
                    $this->addIndexIfMissing($table, 'financial_transactions', 'created_at', 'financial_transactions_created_at_index');
                }
            });
        }

        if (Schema::hasTable('delivery_assignments')) {
            Schema::table('delivery_assignments', function (Blueprint $table) {
                if (Schema::hasColumn('delivery_assignments', 'order_id')) {
                    $this->addIndexIfMissing($table, 'delivery_assignments', 'order_id', 'delivery_assignments_order_id_index');
                }
                if (Schema::hasColumn('delivery_assignments', 'driver_id')) {
                    $this->addIndexIfMissing($table, 'delivery_assignments', 'driver_id', 'delivery_assignments_driver_id_index');
                }
                if (Schema::hasColumn('delivery_assignments', 'status')) {
                    $this->addIndexIfMissing($table, 'delivery_assignments', 'status', 'delivery_assignments_status_index');
                }
            });
        }

        if (Schema::hasTable('product_performance_metrics')) {
            Schema::table('product_performance_metrics', function (Blueprint $table) {
                if (Schema::hasColumn('product_performance_metrics', 'product_id')) {
                    $this->addIndexIfMissing($table, 'product_performance_metrics', 'product_id', 'product_performance_metrics_product_id_index');
                }
                if (Schema::hasColumn('product_performance_metrics', 'metric_date')) {
                    $this->addIndexIfMissing($table, 'product_performance_metrics', 'metric_date', 'product_performance_metrics_metric_date_index');
                }
            });
        }

        if (Schema::hasTable('search_logs')) {
            Schema::table('search_logs', function (Blueprint $table) {
                if (Schema::hasColumn('search_logs', 'user_id')) {
                    $this->addIndexIfMissing($table, 'search_logs', 'user_id', 'search_logs_user_id_index');
                }
                if (Schema::hasColumn('search_logs', 'no_results')) {
                    $this->addIndexIfMissing($table, 'search_logs', 'no_results', 'search_logs_no_results_index');
                }
                if (Schema::hasColumn('search_logs', 'created_at')) {
                    $this->addIndexIfMissing($table, 'search_logs', 'created_at', 'search_logs_created_at_index');
                }
            });
        }

        if (Schema::hasTable('refunds')) {
            Schema::table('refunds', function (Blueprint $table) {
                if (Schema::hasColumn('refunds', 'order_id')) {
                    $this->addIndexIfMissing($table, 'refunds', 'order_id', 'refunds_order_id_index');
                }
                if (Schema::hasColumn('refunds', 'user_id')) {
                    $this->addIndexIfMissing($table, 'refunds', 'user_id', 'refunds_user_id_index');
                }
                if (Schema::hasColumn('refunds', 'approved_by')) {
                    $this->addIndexIfMissing($table, 'refunds', 'approved_by', 'refunds_approved_by_index');
                }
                if (Schema::hasColumn('refunds', 'status')) {
                    $this->addIndexIfMissing($table, 'refunds', 'status', 'refunds_status_index');
                }
            });
        }

        if (Schema::hasTable('support_tickets')) {
            Schema::table('support_tickets', function (Blueprint $table) {
                if (Schema::hasColumn('support_tickets', 'user_id')) {
                    $this->addIndexIfMissing($table, 'support_tickets', 'user_id', 'support_tickets_user_id_index');
                }
                if (Schema::hasColumn('support_tickets', 'status')) {
                    $this->addIndexIfMissing($table, 'support_tickets', 'status', 'support_tickets_status_index');
                }
                if (Schema::hasColumn('support_tickets', 'priority')) {
                    $this->addIndexIfMissing($table, 'support_tickets', 'priority', 'support_tickets_priority_index');
                }
                if (Schema::hasColumn('support_tickets', 'created_at')) {
                    $this->addIndexIfMissing($table, 'support_tickets', 'created_at', 'support_tickets_created_at_index');
                }
            });
        }

        if (Schema::hasTable('dashboard_notifications')) {
            Schema::table('dashboard_notifications', function (Blueprint $table) {
                if (Schema::hasColumn('dashboard_notifications', 'dashboard_type')) {
                    $this->addIndexIfMissing($table, 'dashboard_notifications', 'dashboard_type', 'dashboard_notifications_dashboard_type_index');
                }
                if (Schema::hasColumn('dashboard_notifications', 'user_type')) {
                    $this->addIndexIfMissing($table, 'dashboard_notifications', 'user_type', 'dashboard_notifications_user_type_index');
                }
                if (Schema::hasColumn('dashboard_notifications', 'user_id')) {
                    $this->addIndexIfMissing($table, 'dashboard_notifications', 'user_id', 'dashboard_notifications_user_id_index');
                }
                if (Schema::hasColumn('dashboard_notifications', 'is_read')) {
                    $this->addIndexIfMissing($table, 'dashboard_notifications', 'is_read', 'dashboard_notifications_is_read_index');
                }
                if (Schema::hasColumn('dashboard_notifications', 'created_at')) {
                    $this->addIndexIfMissing($table, 'dashboard_notifications', 'created_at', 'dashboard_notifications_created_at_index');
                }
            });
        }

        if (Schema::hasTable('inventory_movements')) {
            Schema::table('inventory_movements', function (Blueprint $table) {
                if (Schema::hasColumn('inventory_movements', 'product_id')) {
                    $this->addIndexIfMissing($table, 'inventory_movements', 'product_id', 'inventory_movements_product_id_index');
                }
                if (Schema::hasColumn('inventory_movements', 'movement_type')) {
                    $this->addIndexIfMissing($table, 'inventory_movements', 'movement_type', 'inventory_movements_movement_type_index');
                }
                if (Schema::hasColumn('inventory_movements', 'created_at')) {
                    $this->addIndexIfMissing($table, 'inventory_movements', 'created_at', 'inventory_movements_created_at_index');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                try {
                    $table->dropIndex(['status']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['payment_status']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['user_id']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['store_id']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['created_at']);
                } catch (\Throwable $e) {
                }
            });
        }
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                try {
                    $table->dropIndex(['order_id']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['product_id']);
                } catch (\Throwable $e) {
                }
            });
        }
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                try {
                    $table->dropIndex(['is_active']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['stock_quantity']);
                } catch (\Throwable $e) {
                }
            });
        }
        if (Schema::hasTable('financial_transactions')) {
            Schema::table('financial_transactions', function (Blueprint $table) {
                try {
                    $table->dropIndex(['order_id']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['user_id']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['store_id']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['type']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['status']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['created_at']);
                } catch (\Throwable $e) {
                }
            });
        }
        if (Schema::hasTable('delivery_assignments')) {
            Schema::table('delivery_assignments', function (Blueprint $table) {
                try {
                    $table->dropIndex(['order_id']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['driver_id']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['status']);
                } catch (\Throwable $e) {
                }
            });
        }
        if (Schema::hasTable('product_performance_metrics')) {
            Schema::table('product_performance_metrics', function (Blueprint $table) {
                try {
                    $table->dropIndex(['product_id']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['metric_date']);
                } catch (\Throwable $e) {
                }
            });
        }
        if (Schema::hasTable('search_logs')) {
            Schema::table('search_logs', function (Blueprint $table) {
                try {
                    $table->dropIndex(['user_id']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['no_results']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['created_at']);
                } catch (\Throwable $e) {
                }
            });
        }
        if (Schema::hasTable('refunds')) {
            Schema::table('refunds', function (Blueprint $table) {
                try {
                    $table->dropIndex(['order_id']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['user_id']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['approved_by']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['status']);
                } catch (\Throwable $e) {
                }
            });
        }
        if (Schema::hasTable('support_tickets')) {
            Schema::table('support_tickets', function (Blueprint $table) {
                try {
                    $table->dropIndex(['user_id']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['status']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['priority']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['created_at']);
                } catch (\Throwable $e) {
                }
            });
        }
        if (Schema::hasTable('dashboard_notifications')) {
            Schema::table('dashboard_notifications', function (Blueprint $table) {
                try {
                    $table->dropIndex(['dashboard_type']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['user_type']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['user_id']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['is_read']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['created_at']);
                } catch (\Throwable $e) {
                }
            });
        }
        if (Schema::hasTable('inventory_movements')) {
            Schema::table('inventory_movements', function (Blueprint $table) {
                try {
                    $table->dropIndex(['product_id']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['movement_type']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['created_at']);
                } catch (\Throwable $e) {
                }
            });
        }
    }

    private function addIndexIfMissing(Blueprint $table, string $tableName, $columns, string $indexName): void
    {
        if (! $this->hasIndex($tableName, $indexName)) {
            $table->index($columns, $indexName);
        }
    }

    private function hasIndex(string $tableName, string $indexName): bool
    {
        try {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes($tableName);

            return array_key_exists(strtolower($indexName), array_change_key_case($indexes, CASE_LOWER));
        } catch (\Throwable $e) {
            return false;
        }
    }
};

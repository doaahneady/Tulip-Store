<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected function foreignKeyExists(string $table, string $column): bool
    {
        try {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            foreach ($sm->listTableForeignKeys($table) as $fk) {
                if (in_array($column, $fk->getLocalColumns(), true)) {
                    return true;
                }
            }
        } catch (\Throwable $e) {
        }

        return false;
    }

    protected function indexExists(string $table, string $indexName): bool
    {
        try {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes($table);

            return array_key_exists($indexName, $indexes);
        } catch (\Throwable $e) {
            return false;
        }
    }

    protected function foreignKeyNameExists(string $table, string $name): bool
    {
        try {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            foreach ($sm->listTableForeignKeys($table) as $fk) {
                if (strcasecmp($fk->getName(), $name) === 0) {
                    return true;
                }
            }
        } catch (\Throwable $e) {
        }

        return false;
    }

    public function up(): void
    {
        if (Schema::hasTable('support_tickets')) {
            if (! Schema::hasColumn('support_tickets', 'related_order_id')) {
                Schema::table('support_tickets', function (Blueprint $table) {
                    $table->unsignedBigInteger('related_order_id')->nullable()->after('assigned_to');
                });
            }

            $shouldAddForeign = ! $this->foreignKeyExists('support_tickets', 'related_order_id')
                && ! $this->foreignKeyNameExists('support_tickets', 'support_tickets_related_order_id_foreign')
                && ! $this->foreignKeyNameExists('support_tickets', 'fk_support_tickets_related_order_id');
            $shouldAddIndex = ! $this->indexExists('support_tickets', 'support_tickets_related_order_id_index');

            if ($shouldAddForeign || $shouldAddIndex) {
                $driver = Schema::getConnection()->getDriverName();
                Schema::table('support_tickets', function (Blueprint $table) use ($shouldAddForeign, $shouldAddIndex, $driver) {
                    if ($shouldAddForeign && $driver !== 'sqlite') {
                        $table->foreign('related_order_id', 'fk_support_tickets_related_order_id')
                            ->references('id')->on('orders')->onDelete('set null');
                    }
                    if ($shouldAddIndex) {
                        $table->index('related_order_id');
                    }
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('support_tickets') && Schema::hasColumn('support_tickets', 'related_order_id')) {
            $isSqlite = Schema::getConnection()->getDriverName() === 'sqlite';
            $hasForeignDefaultName = $this->foreignKeyNameExists('support_tickets', 'support_tickets_related_order_id_foreign');
            $hasForeignCustomName = $this->foreignKeyNameExists('support_tickets', 'fk_support_tickets_related_order_id');
            $hasIndex = $this->indexExists('support_tickets', 'support_tickets_related_order_id_index');

            Schema::table('support_tickets', function (Blueprint $table) use ($isSqlite, $hasForeignDefaultName, $hasForeignCustomName, $hasIndex) {
                if (! $isSqlite) {
                    if ($hasForeignCustomName) {
                        $table->dropForeign('fk_support_tickets_related_order_id');
                    } elseif ($hasForeignDefaultName) {
                        $table->dropForeign(['related_order_id']);
                    }
                }
                if ($hasIndex) {
                    $table->dropIndex(['related_order_id']);
                }
                $table->dropColumn('related_order_id');
            });
        }
    }
};

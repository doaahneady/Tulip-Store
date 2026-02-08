<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $hasUnique = $this->indexExists('employees', 'employees_email_unique');
        Schema::table('employees', function (Blueprint $table) use ($hasUnique) {
            // Remove user_id as employees will be independent
            if (Schema::hasColumn('employees', 'user_id')) {
                $driver = Schema::getConnection()->getDriverName();
                if ($driver !== 'sqlite') {
                    $table->dropForeign(['user_id']);
                }
                $table->dropColumn('user_id');
            }

            // Add authentication fields
            if (! Schema::hasColumn('employees', 'password')) {
                $table->string('password')->after('email');
            }
            if (! Schema::hasColumn('employees', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }
            if (! Schema::hasColumn('employees', 'remember_token')) {
                $table->rememberToken();
            }

            // Add role fields
            if (! Schema::hasColumn('employees', 'is_admin')) {
                $table->boolean('is_admin')->default(false)->after('status');
            }
            if (! Schema::hasColumn('employees', 'is_it')) {
                $table->boolean('is_it')->default(false)->after('is_admin');
            }
            if (! Schema::hasColumn('employees', 'is_hr')) {
                $table->boolean('is_hr')->default(false)->after('is_it');
            }
            if (! Schema::hasColumn('employees', 'is_finance')) {
                $table->boolean('is_finance')->default(false)->after('is_hr');
            }
            if (! Schema::hasColumn('employees', 'is_driver_supervisor')) {
                $table->boolean('is_driver_supervisor')->default(false)->after('is_finance');
            }
            if (! Schema::hasColumn('employees', 'is_trader')) {
                $table->boolean('is_trader')->default(false)->after('is_driver_supervisor');
            }

            // Make email unique
            if (! $hasUnique) {
                $table->unique('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'password', 'email_verified_at', 'remember_token',
                'is_admin', 'is_it', 'is_hr', 'is_finance', 'is_driver_supervisor', 'is_trader',
            ]);
            $table->dropUnique(['email']);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        });
    }
};

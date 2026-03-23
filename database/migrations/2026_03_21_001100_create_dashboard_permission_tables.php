<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('dashboard_role_permissions')) {
            Schema::create('dashboard_role_permissions', function (Blueprint $table) {
                $table->id();
                $table->string('role_key', 50);
                $table->string('dashboard_key', 50);
                $table->boolean('can_view')->default(true);
                $table->boolean('can_edit')->default(false);
                $table->json('sections')->nullable();
                $table->json('actions')->nullable();
                $table->boolean('can_view_sensitive')->default(false);
                $table->timestamps();
                $table->unique(['role_key', 'dashboard_key'], 'drp_role_dashboard_unique');
            });
        }

        if (! Schema::hasTable('employee_dashboard_overrides')) {
            Schema::create('employee_dashboard_overrides', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id');
                $table->string('dashboard_key', 50);
                $table->boolean('is_override')->default(false);
                $table->boolean('can_view')->nullable();
                $table->boolean('can_edit')->nullable();
                $table->json('sections')->nullable();
                $table->json('actions')->nullable();
                $table->boolean('can_view_sensitive')->nullable();
                $table->timestamps();

                $table->unique(['employee_id', 'dashboard_key'], 'edo_employee_dashboard_unique');
                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('employee_dashboard_overrides')) {
            Schema::dropIfExists('employee_dashboard_overrides');
        }
        if (Schema::hasTable('dashboard_role_permissions')) {
            Schema::dropIfExists('dashboard_role_permissions');
        }
    }
};


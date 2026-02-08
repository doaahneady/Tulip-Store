<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vehicles')) {
            Schema::create('vehicles', function (Blueprint $table) {
                $table->id();
                $table->string('vehicle_type');
                $table->string('plate_number')->unique();
                $table->string('make')->nullable();
                $table->string('model')->nullable();
                $table->unsignedSmallInteger('year')->nullable();
                $table->string('color')->nullable();
                $table->string('vin')->nullable()->unique();
                $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
                $table->text('notes')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('drivers') && ! Schema::hasColumn('drivers', 'vehicle_id')) {
            Schema::table('drivers', function (Blueprint $table) {
                $table->foreignId('vehicle_id')->nullable()->after('user_id')->constrained('vehicles')->nullOnDelete();
                $table->index('vehicle_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('drivers') && Schema::hasColumn('drivers', 'vehicle_id')) {
            Schema::table('drivers', function (Blueprint $table) {
                $table->dropForeign(['vehicle_id']);
                $table->dropIndex(['vehicle_id']);
                $table->dropColumn('vehicle_id');
            });
        }

        Schema::dropIfExists('vehicles');
    }
};

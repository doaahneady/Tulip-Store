<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ip_blacklists')) {
            Schema::create('ip_blacklists', function (Blueprint $table) {
                $table->id();
                $table->string('ip_address', 45)->index();
                $table->string('reason')->nullable();
                $table->foreignId('blocked_by')->nullable()->constrained('employees')->nullOnDelete();
                $table->timestamp('blocked_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->unique(['ip_address', 'is_active']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ip_blacklists')) {
            Schema::dropIfExists('ip_blacklists');
        }
    }
};

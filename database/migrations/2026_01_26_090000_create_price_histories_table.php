<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('price_histories')) {
            Schema::create('price_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->decimal('old_price', 10, 2);
                $table->decimal('new_price', 10, 2);
                $table->unsignedBigInteger('changed_by')->nullable();
                $table->string('change_reason')->nullable();
                $table->timestamp('changed_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->index(['product_id', 'changed_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('price_histories');
    }
};

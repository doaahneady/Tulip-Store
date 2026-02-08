<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('category'); // birthday, wedding, anniversary, etc.
            $table->string('occasion')->nullable(); // specific occasion
            $table->json('images')->nullable(); // array of image paths
            $table->string('size')->nullable(); // small, medium, large
            $table->boolean('is_customizable')->default(false);
            $table->json('customization_options')->nullable(); // colors, messages, etc.
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('delivery_time')->nullable(); // same day, next day, etc.
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('reviews_count')->default(0);
            $table->timestamps();

            $table->index(['category', 'is_active']);
            $table->index(['is_featured', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gifts');
    }
};

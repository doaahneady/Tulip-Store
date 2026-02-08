<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Gift boxes/containers
        Schema::create('gift_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('size', ['small', 'medium', 'large', 'xl'])->default('medium');
            $table->decimal('price', 10, 2);
            $table->string('color')->nullable();
            $table->integer('max_items')->default(5);
            $table->integer('stock')->default(100);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Gift wrapping options
        Schema::create('gift_wrappings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('image')->nullable();
            $table->string('color')->nullable();
            $table->string('pattern')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Gift ribbons
        Schema::create('gift_ribbons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('image')->nullable();
            $table->string('color');
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Gift cards/messages
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('image')->nullable();
            $table->string('occasion')->nullable(); // birthday, wedding, eid, etc.
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Gift fillers (chocolates, flowers, etc.)
        Schema::create('gift_fillers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('category', ['chocolate', 'flower', 'perfume', 'accessory', 'candy', 'toy', 'other']);
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(100);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Custom gift orders
        Schema::create('custom_gifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable();
            $table->foreignId('gift_box_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gift_wrapping_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('gift_ribbon_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('gift_card_id')->nullable()->constrained()->nullOnDelete();
            $table->text('card_message')->nullable();
            $table->string('recipient_name')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['draft', 'completed', 'in_cart', 'ordered'])->default('draft');
            $table->timestamps();
        });

        // Custom gift items (fillers)
        Schema::create('custom_gift_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_gift_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gift_filler_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_gift_items');
        Schema::dropIfExists('custom_gifts');
        Schema::dropIfExists('gift_fillers');
        Schema::dropIfExists('gift_cards');
        Schema::dropIfExists('gift_ribbons');
        Schema::dropIfExists('gift_wrappings');
        Schema::dropIfExists('gift_boxes');
    }
};

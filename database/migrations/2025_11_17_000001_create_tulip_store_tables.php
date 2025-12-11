<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Categories table
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        }

        // Products table
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('details')->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();
            $table->text('images')->nullable(); // JSON array of images
            $table->integer('rating')->default(0);
            $table->integer('reviews_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('category_id');
            $table->index('is_active');
        });
        }

        // Product attributes table
        if (!Schema::hasTable('product_attributes')) {
            Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('attribute_name'); // e.g., 'Color', 'Size'
            $table->string('attribute_value'); // e.g., 'Red', 'Large'
            $table->timestamps();
        });
        }

        // Carts table
        if (!Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
        }

        // Cart items table
        if (!Schema::hasTable('cart_items')) {
            Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2); // Price at time of adding to cart
            $table->timestamps();
            $table->unique(['cart_id', 'product_id']);
        });
        }

        // Orders table
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->enum('status', ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->text('shipping_address');
            $table->string('shipping_method')->nullable();
            $table->string('tracking_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('status');
        });
        }

        // Order items table
        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('product_name');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
        }

        // Reviews table
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->boolean('is_verified_purchase')->default(false);
            $table->timestamps();
            $table->unique(['product_id', 'user_id']);
        });
        }

        // Wishlists table
        if (!Schema::hasTable('wishlists')) {
            Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'product_id']);
        });
        }

        // Coupons table
        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 10, 2);
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->datetime('valid_from');
            $table->datetime('valid_to');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};

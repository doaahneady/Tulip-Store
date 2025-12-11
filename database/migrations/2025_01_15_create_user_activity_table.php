<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_activity', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('activity_type'); // view, search, cart_add, purchase
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('search_query')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['session_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
        
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->unsignedBigInteger('user_id')->nullable()->unique();
            $table->json('favorite_categories')->nullable();
            $table->json('search_keywords')->nullable();
            $table->json('viewed_products')->nullable();
            $table->json('purchased_products')->nullable();
            $table->integer('activity_score')->default(0);
            $table->timestamp('last_activity')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_activity');
        Schema::dropIfExists('user_preferences');
    }
};

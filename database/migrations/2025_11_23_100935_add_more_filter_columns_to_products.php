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
        Schema::table('products', function (Blueprint $table) {
            // Clothing & Fashion filters (skip existing: color, size, material)
            if (!Schema::hasColumn('products', 'fit')) {
                $table->string('fit')->nullable(); // Slim, Regular, Loose
            }
            if (!Schema::hasColumn('products', 'sleeve_length')) {
                $table->string('sleeve_length')->nullable(); // Short, Long, Sleeveless
            }
            if (!Schema::hasColumn('products', 'pattern')) {
                $table->string('pattern')->nullable(); // Solid, Striped, Floral
            }
            
            // Shoes filters
            if (!Schema::hasColumn('products', 'shoe_size')) {
                $table->string('shoe_size')->nullable();
            }
            if (!Schema::hasColumn('products', 'shoe_type')) {
                $table->string('shoe_type')->nullable(); // Sneakers, Boots, Sandals
            }
            
            // Electronics filters
            if (!Schema::hasColumn('products', 'screen_size')) {
                $table->string('screen_size')->nullable();
            }
            if (!Schema::hasColumn('products', 'storage')) {
                $table->string('storage')->nullable();
            }
            if (!Schema::hasColumn('products', 'ram')) {
                $table->string('ram')->nullable();
            }
            if (!Schema::hasColumn('products', 'processor')) {
                $table->string('processor')->nullable();
            }
            if (!Schema::hasColumn('products', 'battery')) {
                $table->string('battery')->nullable();
            }
            if (!Schema::hasColumn('products', 'connectivity')) {
                $table->string('connectivity')->nullable(); // WiFi, Bluetooth, 5G
            }
            
            // Books filters (skip existing: author, genre, pages)
            if (!Schema::hasColumn('products', 'publisher')) {
                $table->string('publisher')->nullable();
            }
            if (!Schema::hasColumn('products', 'language')) {
                $table->string('language')->nullable();
            }
            if (!Schema::hasColumn('products', 'format')) {
                $table->string('format')->nullable(); // Hardcover, Paperback, eBook
            }
            
            // Toys filters (skip existing: age_range)
            if (!Schema::hasColumn('products', 'toy_type')) {
                $table->string('toy_type')->nullable(); // Educational, Action, Puzzle
            }
            
            // Home & Kitchen filters
            if (!Schema::hasColumn('products', 'room')) {
                $table->string('room')->nullable(); // Kitchen, Bedroom, Living Room
            }
            if (!Schema::hasColumn('products', 'capacity')) {
                $table->string('capacity')->nullable();
            }
            if (!Schema::hasColumn('products', 'power')) {
                $table->string('power')->nullable();
            }
            
            // Sports filters
            if (!Schema::hasColumn('products', 'sport_type')) {
                $table->string('sport_type')->nullable(); // Football, Basketball, Yoga
            }
            if (!Schema::hasColumn('products', 'skill_level')) {
                $table->string('skill_level')->nullable(); // Beginner, Intermediate, Pro
            }
            
            // General filters
            if (!Schema::hasColumn('products', 'weight')) {
                $table->string('weight')->nullable();
            }
            if (!Schema::hasColumn('products', 'dimensions')) {
                $table->string('dimensions')->nullable();
            }
            if (!Schema::hasColumn('products', 'warranty')) {
                $table->string('warranty')->nullable();
            }
            if (!Schema::hasColumn('products', 'free_shipping')) {
                $table->boolean('free_shipping')->default(false);
            }
            if (!Schema::hasColumn('products', 'on_sale')) {
                $table->boolean('on_sale')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = [
                'fit', 'sleeve_length', 'pattern',
                'shoe_size', 'shoe_type',
                'screen_size', 'storage', 'ram', 'processor', 'battery', 'connectivity',
                'publisher', 'language', 'format',
                'toy_type',
                'room', 'capacity', 'power',
                'sport_type', 'skill_level',
                'weight', 'dimensions', 'warranty', 'free_shipping', 'on_sale'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

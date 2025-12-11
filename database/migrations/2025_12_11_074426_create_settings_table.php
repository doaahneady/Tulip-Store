<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, number, boolean, json
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
        // Insert default goals
        DB::table('settings')->insert([
            [
                'key' => 'monthly_sales_goal',
                'value' => '50000',
                'type' => 'number',
                'description' => 'Monthly sales target in USD',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'yearly_sales_goal',
                'value' => '500000',
                'type' => 'number',
                'description' => 'Yearly sales target in USD',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

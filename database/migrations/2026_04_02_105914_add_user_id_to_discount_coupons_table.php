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
        Schema::table('discount_coupons', function (Blueprint $table) {
            if (!Schema::hasColumn('discount_coupons', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('code')->constrained('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discount_coupons', function (Blueprint $table) {
            if (Schema::hasColumn('discount_coupons', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};

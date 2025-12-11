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
        Schema::table('users', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('is_admin');
            $table->string('tags')->nullable()->after('notes'); // VIP, Wholesale, etc.
            $table->boolean('newsletter_subscribed')->default(false)->after('tags');
            $table->decimal('lifetime_value', 10, 2)->default(0)->after('newsletter_subscribed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notes', 'tags', 'newsletter_subscribed', 'lifetime_value']);
        });
    }
};

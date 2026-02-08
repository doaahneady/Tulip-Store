<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('compliance_documents')) {
            Schema::create('compliance_documents', function (Blueprint $table) {
                $table->id();
                $table->string('doc_type');
                $table->string('period')->nullable();
                $table->string('file_url');
                $table->unsignedBigInteger('filed_by')->nullable();
                $table->timestamp('filed_at')->nullable();
                $table->timestamps();

                $table->index(['doc_type', 'period']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('compliance_documents')) {
            Schema::drop('compliance_documents');
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Chart of Accounts
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_code')->unique();
            $table->string('account_name');
            $table->enum('account_type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->enum('account_subtype', ['current', 'non_current', 'operating', 'non_operating'])->nullable();
            $table->foreignId('parent_account_id')->nullable()->constrained('chart_of_accounts')->onDelete('set null');
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('account_code');
            $table->index('account_type');
        });

        // Journal Entries
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number')->unique();
            $table->date('entry_date');
            $table->enum('entry_type', ['general', 'sales', 'purchase', 'payment', 'receipt', 'adjustment']);
            $table->text('description');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['draft', 'posted', 'approved', 'reversed'])->default('draft');
            $table->timestamp('posted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('reversed_entry_id')->nullable()->constrained('journal_entries')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('entry_number');
            $table->index('entry_date');
            $table->index('status');
        });

        // Journal Entry Lines
        Schema::create('journal_entry_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained('chart_of_accounts')->onDelete('restrict');
            $table->enum('type', ['debit', 'credit']);
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('journal_entry_id');
            $table->index('account_id');
        });

        // Fiscal Periods
        Schema::create('fiscal_periods', function (Blueprint $table) {
            $table->id();
            $table->string('period_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('period_type', ['month', 'quarter', 'year']);
            $table->boolean('is_closed')->default(false);
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('start_date');
            $table->index('end_date');
            $table->index('is_closed');
        });

        // Financial Reports
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();
            $table->enum('report_type', ['balance_sheet', 'income_statement', 'cash_flow', 'trial_balance', 'general_ledger']);
            $table->date('report_date');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->json('report_data');
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('report_type');
            $table->index('report_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_reports');
        Schema::dropIfExists('fiscal_periods');
        Schema::dropIfExists('journal_entry_lines');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('chart_of_accounts');
    }
};

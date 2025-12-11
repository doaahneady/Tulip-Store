<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $table = 'chart_of_accounts';

    protected $fillable = [
        'account_code',
        'account_name',
        'account_name_en',
        'account_type',
        'account_subtype',
        'parent_account_id',
        'opening_balance',
        'current_balance',
        'is_active',
        'description',
        'normal_balance'
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Account Types
    const TYPE_ASSET = 'asset';
    const TYPE_LIABILITY = 'liability';
    const TYPE_EQUITY = 'equity';
    const TYPE_REVENUE = 'revenue';
    const TYPE_EXPENSE = 'expense';

    public function parent()
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_account_id');
    }

    public function children()
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_account_id');
    }

    public function journalLines()
    {
        return $this->hasMany(JournalEntryLine::class, 'account_id');
    }

    public function getDebitTotal()
    {
        return $this->journalLines()->where('type', 'debit')->sum('amount');
    }

    public function getCreditTotal()
    {
        return $this->journalLines()->where('type', 'credit')->sum('amount');
    }

    public function calculateBalance()
    {
        $debits = $this->getDebitTotal();
        $credits = $this->getCreditTotal();
        
        // Assets and Expenses have normal debit balance
        if (in_array($this->account_type, [self::TYPE_ASSET, self::TYPE_EXPENSE])) {
            return $this->opening_balance + $debits - $credits;
        }
        // Liabilities, Equity, Revenue have normal credit balance
        return $this->opening_balance + $credits - $debits;
    }

    public static function getAccountTypes()
    {
        return [
            self::TYPE_ASSET => 'أصول',
            self::TYPE_LIABILITY => 'التزامات',
            self::TYPE_EQUITY => 'حقوق ملكية',
            self::TYPE_REVENUE => 'إيرادات',
            self::TYPE_EXPENSE => 'مصروفات'
        ];
    }
}

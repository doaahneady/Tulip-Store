<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_number',
        'entry_date',
        'entry_type',
        'description',
        'created_by',
        'approved_by',
        'status',
        'posted_at',
        'approved_at',
        'reversed_entry_id',
        'notes',
        'reference_type',
        'reference_id',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'posted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    const STATUS_DRAFT = 'draft';

    const STATUS_POSTED = 'posted';

    const STATUS_APPROVED = 'approved';

    const STATUS_REVERSED = 'reversed';

    const TYPE_GENERAL = 'general';

    const TYPE_SALES = 'sales';

    const TYPE_PURCHASE = 'purchase';

    const TYPE_PAYMENT = 'payment';

    const TYPE_RECEIPT = 'receipt';

    const TYPE_ADJUSTMENT = 'adjustment';

    public function lines()
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function reversedEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'reversed_entry_id');
    }

    public function getTotalDebit()
    {
        return $this->lines()->where('type', 'debit')->sum('amount');
    }

    public function getTotalCredit()
    {
        return $this->lines()->where('type', 'credit')->sum('amount');
    }

    public function isBalanced()
    {
        return abs($this->getTotalDebit() - $this->getTotalCredit()) < 0.01;
    }

    public static function generateEntryNumber()
    {
        $lastEntry = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastEntry ? intval(substr($lastEntry->entry_number, 3)) + 1 : 1;

        return 'JE-'.str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    public static function getEntryTypes()
    {
        return [
            self::TYPE_GENERAL => 'قيد عام',
            self::TYPE_SALES => 'قيد مبيعات',
            self::TYPE_PURCHASE => 'قيد مشتريات',
            self::TYPE_PAYMENT => 'قيد دفع',
            self::TYPE_RECEIPT => 'قيد قبض',
            self::TYPE_ADJUSTMENT => 'قيد تسوية',
        ];
    }
}

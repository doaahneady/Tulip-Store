<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiscalPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_name',
        'start_date',
        'end_date',
        'period_type',
        'is_closed',
        'closed_at',
        'closed_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_closed' => 'boolean',
        'closed_at' => 'datetime',
    ];

    public function closedByUser()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public static function getCurrentPeriod()
    {
        return self::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('is_closed', false)
            ->first();
    }
}

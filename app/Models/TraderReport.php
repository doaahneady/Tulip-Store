<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraderReport extends Model
{
    use HasFactory;

    protected $table = 'trader_reports';

    protected $fillable = [
        'trader_id',
        'report_type',
        'title',
        'description',
        'report_data',
        'file_url',
        'submitted_to',
        'status',
        'admin_response',
        'responded_by',
        'responded_at',
    ];

    protected $casts = [
        'report_data' => 'array',
        'responded_at' => 'datetime',
    ];

    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }

    public function responder()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }
}

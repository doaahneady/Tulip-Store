<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardQuickAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'dashboard_type',
        'action_type',
        'user_type',
        'user_id',
        'description',
        'affected_records',
        'status',
        'error_message',
        'parameters',
    ];

    protected $casts = [
        'affected_records' => 'integer',
        'parameters' => 'array',
    ];

    public function user()
    {
        return $this->morphTo();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'priority',
        'is_read',
        'is_resolved',
        'resolved_by',
        'resolved_at',
        'resolution_notes',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}

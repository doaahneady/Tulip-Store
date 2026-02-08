<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicketReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'author_type',
        'author_id',
        'message',
        'attachments',
        'is_internal',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_internal' => 'boolean',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function author()
    {
        return $this->morphTo();
    }

    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }

    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    public function getAuthorNameAttribute()
    {
        return $this->author?->name ?? $this->author?->full_name ?? 'Unknown';
    }

    public function getAuthorTypeDisplayAttribute()
    {
        return match ($this->author_type) {
            'App\\Models\\User' => 'Customer',
            'App\\Models\\Employee' => 'Support Agent',
            default => 'System',
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnhancedSupportTicketReply extends Model
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
        return $this->belongsTo(EnhancedSupportTicket::class, 'ticket_id');
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
        if (! $this->author) {
            return 'Unknown';
        }

        if ($this->author_type === 'App\\Models\\Employee') {
            return $this->author->first_name.' '.$this->author->last_name;
        }

        return $this->author->name ?? 'Customer';
    }

    public function getIsFromEmployeeAttribute()
    {
        return $this->author_type === 'App\\Models\\Employee';
    }

    public function getIsFromCustomerAttribute()
    {
        return $this->author_type === 'App\\Models\\User';
    }
}

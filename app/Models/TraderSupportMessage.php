<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraderSupportMessage extends Model
{
    use HasFactory;

    protected $table = 'trader_support_messages';

    protected $fillable = [
        'ticket_id',
        'sender_id',
        'sender_type',
        'message',
        'attachments',
        'is_internal_note',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_internal_note' => 'boolean',
    ];

    public function ticket()
    {
        return $this->belongsTo(TraderSupportTicket::class, 'ticket_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}

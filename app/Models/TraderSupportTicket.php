<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraderSupportTicket extends Model
{
    use HasFactory;

    protected $table = 'trader_support_tickets';

    protected $fillable = [
        'trader_id',
        'subject',
        'category',
        'priority',
        'description',
        'status',
        'assigned_to',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public const STATUS_OPEN = 'open';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_WAITING_TRADER = 'waiting_trader';

    public const STATUS_RESOLVED = 'resolved';

    public const STATUS_CLOSED = 'closed';

    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages()
    {
        return $this->hasMany(TraderSupportMessage::class, 'ticket_id');
    }
}

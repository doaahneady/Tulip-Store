<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomGift extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'gift_box_id',
        'gift_wrapping_id',
        'gift_ribbon_id',
        'gift_card_id',
        'card_message',
        'recipient_name',
        'total_price',
        'status',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function box()
    {
        return $this->belongsTo(GiftBox::class, 'gift_box_id');
    }

    public function wrapping()
    {
        return $this->belongsTo(GiftWrapping::class, 'gift_wrapping_id');
    }

    public function ribbon()
    {
        return $this->belongsTo(GiftRibbon::class, 'gift_ribbon_id');
    }

    public function card()
    {
        return $this->belongsTo(GiftCard::class, 'gift_card_id');
    }

    public function items()
    {
        return $this->hasMany(CustomGiftItem::class);
    }
}

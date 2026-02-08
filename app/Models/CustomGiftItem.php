<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomGiftItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_gift_id',
        'gift_filler_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    public function customGift()
    {
        return $this->belongsTo(CustomGift::class);
    }

    public function filler()
    {
        return $this->belongsTo(GiftFiller::class, 'gift_filler_id');
    }
}

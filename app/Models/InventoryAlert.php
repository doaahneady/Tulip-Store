<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'alert_type',
        'current_quantity',
        'threshold_quantity',
        'severity',
        'is_resolved',
        'resolved_at',
        'resolution_notes',
    ];

    protected $casts = [
        'current_quantity' => 'integer',
        'threshold_quantity' => 'integer',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    public function resolve($notes = null)
    {
        $this->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolution_notes' => $notes,
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'supplier_name',
        'supplier_contact',
        'status',
        'expected_delivery_date',
        'total_cost',
        'created_by',
        'approved_by',
        'approved_at',
        'received_at',
        'notes',
    ];

    protected $casts = [
        'total_cost' => 'decimal:2',
        'approved_at' => 'datetime',
        'received_at' => 'datetime',
        'expected_delivery_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}

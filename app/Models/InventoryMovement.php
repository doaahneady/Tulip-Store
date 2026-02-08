<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'previous_stock',
        'new_stock',
        'reason',
        'notes',
        'order_id',
        'created_by',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function getTypeColorAttribute()
    {
        return match ($this->type) {
            'in' => 'text-green-600 bg-green-100',
            'out' => 'text-red-600 bg-red-100',
            'adjustment' => 'text-yellow-600 bg-yellow-100',
            'transfer' => 'text-blue-600 bg-blue-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    public function getTypeIconAttribute()
    {
        return match ($this->type) {
            'in' => 'fa-arrow-up',
            'out' => 'fa-arrow-down',
            'adjustment' => 'fa-edit',
            'transfer' => 'fa-exchange-alt',
            default => 'fa-box',
        };
    }

    public static function recordMovement($product, $type, $quantity, $reason = null, $orderId = null, $notes = null)
    {
        $previousStock = $product->stock_quantity;

        $newStock = match ($type) {
            'in', 'adjustment' => $previousStock + abs($quantity),
            'out' => $previousStock - abs($quantity),
            'transfer' => $previousStock - abs($quantity),
            default => $previousStock,
        };

        $newStock = max(0, $newStock);
        $updates = ['stock_quantity' => $newStock];
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'status') && ($product->track_inventory ?? true)) {
            if ($newStock <= 0 && ($product->status ?? null) === 'active') {
                $updates['status'] = 'out_of_stock';
            }
            if ($newStock > 0 && ($product->status ?? null) === 'out_of_stock') {
                $updates['status'] = 'active';
            }
        }

        $product->update($updates);

        // Record movement
        return static::create([
            'product_id' => $product->id,
            'type' => $type,
            'quantity' => $quantity,
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'reason' => $reason,
            'notes' => $notes,
            'order_id' => $orderId,
            'created_by' => auth('employee')->id(),
        ]);
    }

    public static function recordDiscrepancyAdjustment($product, $countedQuantity, $expectedQuantity, $notes = null, $resolvedBy = null)
    {
        $previousStock = $product->stock_quantity;
        $counted = max(0, (int) $countedQuantity);
        $expected = max(0, (int) $expectedQuantity);
        $difference = $counted - $expected;

        $product->update(['stock_quantity' => $counted]);

        return static::create([
            'product_id' => $product->id,
            'type' => 'adjustment',
            'quantity' => abs($difference),
            'previous_stock' => $previousStock,
            'new_stock' => $counted,
            'reason' => 'audit_discrepancy',
            'notes' => $notes,
            'order_id' => null,
            'created_by' => auth('employee')->id(),
            'metadata' => [
                'audit_expected' => $expected,
                'audit_counted' => $counted,
                'resolved_by' => $resolvedBy,
            ],
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'attribute_key',
        'type',
        'value',
        'options',
        'is_custom',
        'sort_order',
        'is_required',
        'rules',
        'value_text',
        'value_number',
        'value_date',
        'value_json',
    ];

    protected $casts = [
        'options' => 'array',
        'is_custom' => 'boolean',
        'is_required' => 'boolean',
        'rules' => 'array',
        'value_json' => 'array',
        'value_number' => 'decimal:2',
        'value_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

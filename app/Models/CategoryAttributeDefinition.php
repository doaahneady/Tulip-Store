<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class CategoryAttributeDefinition extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'type',
        'options',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function forCategory(?int $categoryId)
    {
        if (! Schema::hasTable('category_attribute_definitions')) {
            return collect();
        }

        return static::query()
            ->where(function ($q) use ($categoryId) {
                $q->whereNull('category_id');
                if ($categoryId) {
                    $q->orWhere('category_id', $categoryId);
                }
            })
            ->orderBy('category_id')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'image',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'display_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        $path = str_replace('\\', '/', trim((string) ($this->getAttribute('image') ?? '')));
        if ($path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, '/storage/') || str_starts_with($path, '/images/') || str_starts_with($path, '/')) {
            return $path;
        }

        if (str_starts_with($path, 'public/')) {
            $path = substr($path, strlen('public/'));
        }

        if (str_starts_with($path, 'images/')) {
            return '/'.$path;
        }

        if (file_exists(public_path($path))) {
            return '/'.$path;
        }

        $clean = preg_replace('#^storage/#', '', $path) ?? $path;
        $clean = ltrim($clean, '/');
        if ($clean !== '') {
            return '/storage/'.$clean;
        }

        return null;
    }
}


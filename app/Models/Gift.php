<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Gift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'occasion',
        'images',
        'size',
        'is_customizable',
        'customization_options',
        'stock_quantity',
        'is_featured',
        'is_active',
        'delivery_time',
        'rating',
        'reviews_count',
    ];

    protected $casts = [
        'images' => 'array',
        'customization_options' => 'array',
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_customizable' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'main_image',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2).' ل.س';
    }

    public function getMainImageAttribute()
    {
        $images = $this->images ?? [];

        $candidate = ! empty($images) ? (string) $images[0] : '';
        $candidate = trim($candidate);

        if ($candidate === '') {
            return '/images/birthday_card.jpeg';
        }

        if (Str::startsWith($candidate, ['http://', 'https://'])) {
            return $candidate;
        }

        $candidate = preg_replace('#^(/storage/)+#', '/storage/', $candidate);

        if (Str::startsWith($candidate, '/storage/')) {
            $relative = ltrim(Str::after($candidate, '/storage/'), '/');
            return $relative !== '' ? '/storage/'.$relative : '/images/birthday_card.jpeg';
        }

        if (Str::startsWith($candidate, '/images/')) {
            return $candidate;
        }
        if (Str::startsWith($candidate, '/')) {
            return $candidate;
        }
        if (Str::startsWith($candidate, 'images/')) {
            return '/'.$candidate;
        }
        if (file_exists(public_path($candidate))) {
            return '/'.$candidate;
        }

        $relative = ltrim(preg_replace('#^(public/|storage/)#', '', $candidate), '/');
        if ($relative === '') {
            return '/images/birthday_card.jpeg';
        }

        return Storage::disk('public')->url($relative);
    }

    public function getIsInStockAttribute()
    {
        return $this->stock_quantity > 0;
    }

    public function getCategoryColorAttribute()
    {
        return match ($this->category) {
            'birthday' => 'text-pink-600 bg-pink-100',
            'wedding' => 'text-purple-600 bg-purple-100',
            'anniversary' => 'text-red-600 bg-red-100',
            'graduation' => 'text-blue-600 bg-blue-100',
            'baby' => 'text-green-600 bg-green-100',
            'valentine' => 'text-red-600 bg-red-100',
            'mothers_day' => 'text-pink-600 bg-pink-100',
            'fathers_day' => 'text-blue-600 bg-blue-100',
            'christmas' => 'text-green-600 bg-green-100',
            'eid' => 'text-yellow-600 bg-yellow-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    public function getCategoryNameAttribute()
    {
        return match ($this->category) {
            'birthday' => 'عيد ميلاد',
            'wedding' => 'زفاف',
            'anniversary' => 'ذكرى سنوية',
            'graduation' => 'تخرج',
            'baby' => 'مولود جديد',
            'valentine' => 'عيد الحب',
            'mothers_day' => 'عيد الأم',
            'fathers_day' => 'عيد الأب',
            'christmas' => 'عيد الميلاد',
            'eid' => 'عيد',
            'general' => 'عام',
            default => $this->category,
        };
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use Illuminate\Http\Request;

class GiftController extends Controller
{
    public function index(Request $request)
    {
        $query = Gift::active()->inStock();

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filter by occasion
        if ($request->has('occasion') && $request->occasion) {
            $query->where('occasion', $request->occasion);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('occasion', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'featured');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
        }

        $gifts = $query->paginate(12);
        $categories = $this->getCategories();
        $featuredGifts = Gift::active()->inStock()->featured()->take(6)->get();

        return view('gifts.index', compact('gifts', 'categories', 'featuredGifts'));
    }

    public function show(Gift $gift)
    {
        if (! $gift->is_active || $gift->stock_quantity <= 0) {
            abort(404);
        }

        $relatedGifts = Gift::active()
            ->inStock()
            ->where('category', $gift->category)
            ->where('id', '!=', $gift->id)
            ->take(4)
            ->get();

        return view('gifts.show', compact('gift', 'relatedGifts'));
    }

    public function category($category)
    {
        $gifts = Gift::active()
            ->inStock()
            ->where('category', $category)
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = $this->getCategories();
        $categoryName = $this->getCategoryName($category);

        return view('gifts.category', compact('gifts', 'categories', 'category', 'categoryName'));
    }

    private function getCategories()
    {
        return [
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
        ];
    }

    private function getCategoryName($category)
    {
        $categories = $this->getCategories();

        return $categories[$category] ?? $category;
    }
}

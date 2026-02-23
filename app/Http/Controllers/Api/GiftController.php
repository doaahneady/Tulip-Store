<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gift;
use Illuminate\Http\Request;

class GiftController extends Controller
{
    public function index(Request $request)
    {
        $query = Gift::active();

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
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

        $gifts = $query->paginate($request->get('per_page', 12));

        return response()->json([
            'success' => true,
            'data' => $gifts->items(),
            'pagination' => [
                'current_page' => $gifts->currentPage(),
                'last_page' => $gifts->lastPage(),
                'per_page' => $gifts->perPage(),
                'total' => $gifts->total(),
            ],
        ]);
    }

    public function show($id)
    {
        $gift = Gift::active()->find($id);

        if (! $gift) {
            return response()->json([
                'success' => false,
                'message' => 'Gift not found',
            ], 404);
        }

        $relatedGifts = Gift::active()
            ->where('category', $gift->category)
            ->where('id', '!=', $gift->id)
            ->take(4)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $gift,
            'related_gifts' => $relatedGifts,
        ]);
    }

    public function featured()
    {
        $gifts = Gift::active()->featured()->take(6)->get();
        if ($gifts->isEmpty()) {
            $gifts = Gift::active()->orderBy('created_at', 'desc')->take(6)->get();
        }

        return response()->json([
            'success' => true,
            'data' => $gifts,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }

        $gifts = Gift::active()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('category', 'like', "%{$query}%")
                    ->orWhere('occasion', 'like', "%{$query}%");
            })
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $gifts,
        ]);
    }

    public function categories()
    {
        $categories = [
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

        $categoriesWithCounts = [];
        foreach ($categories as $key => $name) {
            $count = Gift::active()->where('category', $key)->count();
            $categoriesWithCounts[] = [
                'key' => $key,
                'name' => $name,
                'count' => $count,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $categoriesWithCounts,
        ]);
    }

    public function byCategory($category)
    {
        $gifts = Gift::active()
            ->where('category', $category)
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $gifts->items(),
            'pagination' => [
                'current_page' => $gifts->currentPage(),
                'last_page' => $gifts->lastPage(),
                'per_page' => $gifts->perPage(),
                'total' => $gifts->total(),
            ],
        ]);
    }
}

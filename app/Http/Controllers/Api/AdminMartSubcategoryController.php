<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminMartSubcategoryController extends Controller
{
    private function authorizeMart(Request $request): void
    {
        $user = $request->user();
        if (! ($user instanceof Employee)) {
            abort(403, 'Forbidden');
        }
        if (! $user->canAccessDashboard('mart')) {
            abort(403, 'Forbidden');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeMart($request);
        abort_unless(Schema::hasTable('subcategories') && Schema::hasTable('categories'), 404);

        $q = Subcategory::query()->with('category');

        if ($request->filled('category_id')) {
            $q->where('category_id', (int) $request->input('category_id'));
        } elseif ($request->filled('category')) {
            $slug = trim((string) $request->input('category'));
            if ($slug !== '') {
                $q->whereHas('category', fn ($cq) => $cq->where('slug', $slug));
            }
        }

        if (Schema::hasColumn('categories', 'market')) {
            $q->whereHas('category', fn ($cq) => $cq->where('market', 'mart'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            if ($search !== '') {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            }
        }

        if (Schema::hasColumn('subcategories', 'display_order')) {
            $q->orderBy('display_order');
        }
        $q->orderBy('name');

        return response()->json([
            'data' => $q->get()->map(function (Subcategory $s) {
                return [
                    'id' => $s->id,
                    'category_id' => $s->category_id,
                    'name' => $s->name,
                    'slug' => $s->slug,
                    'display_order' => (int) ($s->display_order ?? 0),
                    'is_active' => (bool) ($s->is_active ?? true),
                    'category' => $s->category ? [
                        'id' => $s->category->id,
                        'name' => $s->category->name,
                        'slug' => $s->category->slug,
                    ] : null,
                ];
            })->values()->all(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeMart($request);
        abort_unless(Schema::hasTable('subcategories') && Schema::hasTable('categories'), 404);

        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $category = Category::query()->findOrFail((int) $validated['category_id']);
        if (Schema::hasColumn('categories', 'market') && (string) ($category->market ?? '') !== 'mart') {
            return response()->json(['message' => 'Category must be Mart'], 422);
        }

        $slug = trim((string) ($validated['slug'] ?? ''));
        $slug = $slug === '' ? Str::slug($validated['name']) : Str::slug($slug);
        if ($slug === '') {
            $slug = 'subcategory';
        }
        $baseSlug = $slug;
        $i = 0;
        while (Subcategory::query()->where('category_id', $category->id)->where('slug', $slug)->exists() && $i < 50) {
            $slug = $baseSlug.'-'.random_int(1000, 9999);
            $i++;
        }

        $subcategory = Subcategory::create([
            'category_id' => $category->id,
            'name' => $validated['name'],
            'slug' => $slug,
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        Cache::forget('mart:navigation:v1');

        return response()->json([
            'success' => true,
            'data' => $subcategory->fresh(),
        ], 201);
    }

    public function show(Request $request, Subcategory $subcategory)
    {
        $this->authorizeMart($request);
        abort_unless(Schema::hasTable('subcategories'), 404);

        return response()->json([
            'data' => $subcategory->load('category'),
        ]);
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $this->authorizeMart($request);
        abort_unless(Schema::hasTable('subcategories') && Schema::hasTable('categories'), 404);

        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $category = Category::query()->findOrFail((int) $validated['category_id']);
        if (Schema::hasColumn('categories', 'market') && (string) ($category->market ?? '') !== 'mart') {
            return response()->json(['message' => 'Category must be Mart'], 422);
        }

        $slug = trim((string) ($validated['slug'] ?? ''));
        $slug = $slug === '' ? Str::slug($validated['name']) : Str::slug($slug);
        if ($slug === '') {
            $slug = 'subcategory';
        }

        $baseSlug = $slug;
        $i = 0;
        while (Subcategory::query()
            ->where('category_id', $category->id)
            ->where('slug', $slug)
            ->where('id', '!=', $subcategory->id)
            ->exists() && $i < 50) {
            $slug = $baseSlug.'-'.random_int(1000, 9999);
            $i++;
        }

        $subcategory->update([
            'category_id' => $category->id,
            'name' => $validated['name'],
            'slug' => $slug,
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        Cache::forget('mart:navigation:v1');

        return response()->json([
            'success' => true,
            'data' => $subcategory->fresh(),
        ]);
    }

    public function destroy(Request $request, Subcategory $subcategory)
    {
        $this->authorizeMart($request);
        abort_unless(Schema::hasTable('subcategories'), 404);

        $subcategory->delete();
        Cache::forget('mart:navigation:v1');

        return response()->json(['success' => true]);
    }

    public function reorder(Request $request, Category $category)
    {
        $this->authorizeMart($request);
        abort_unless(Schema::hasTable('subcategories') && Schema::hasColumn('subcategories', 'display_order'), 404);

        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:subcategories,id',
        ]);

        $allowed = Subcategory::query()->where('category_id', $category->id)->pluck('id')->map(fn ($x) => (int) $x)->all();
        $allowedSet = array_fill_keys($allowed, true);
        $filtered = array_values(array_filter($validated['order'], fn ($id) => isset($allowedSet[(int) $id])));

        foreach ($filtered as $i => $id) {
            Subcategory::query()->whereKey((int) $id)->update(['display_order' => $i]);
        }

        Cache::forget('mart:navigation:v1');

        return response()->json(['success' => true]);
    }
}


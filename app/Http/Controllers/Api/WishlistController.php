<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WishlistController extends Controller
{
    protected function resolveWishlistImage($p): string
    {
        $raw = $p->primary_image_url
            ?? $p->image
            ?? ($p->photo ?? null)
            ?? ($p->image_path ?? null)
            ?? '';

        $path = trim(str_replace('\\', '/', (string) $raw));
        if ($path === '') {
            return '/images/tulip_store.jpg';
        }
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        if (Str::startsWith($path, '/')) {
            if (Str::startsWith($path, '/storage/public/')) {
                return '/storage/'.ltrim(Str::after($path, '/storage/public/'), '/');
            }
            return $path;
        }
        $cleaned = ltrim((string) preg_replace('#^(storage/|public/)#', '', $path), '/');
        return '/storage/'.$cleaned;
    }

    public function index()
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        $items = Wishlist::with('product')
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($w) {
                $p = $w->product;

                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $p->discount_price ?? $p->price,
                    'image' => $this->resolveWishlistImage($p),
                ];
            });

        return response()->json([
            'success' => true,
            'items' => $items,
            'count' => $items->count(),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = Auth::user();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        $product = Product::findOrFail($request->product_id);

        Wishlist::firstOrCreate([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $count = Wishlist::where('user_id', $user->id)->count();

        return response()->json([
            'success' => true,
            'count' => $count,
        ], 201);
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = Auth::user();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        $existing = Wishlist::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->delete();
            $toggled = 'removed';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
            ]);
            $toggled = 'added';
        }

        $count = Wishlist::where('user_id', $user->id)->count();

        return response()->json([
            'success' => true,
            'action' => $toggled,
            'count' => $count,
        ]);
    }

    public function remove($productId)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->delete();

        $count = Wishlist::where('user_id', $user->id)->count();

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    public function getUserFavorites($userId)
    {
        $items = Wishlist::with('product')
            ->where('user_id', $userId)
            ->get()
            ->map(function ($w) {
                $p = $w->product;

                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $p->discount_price ?? $p->price,
                    'image' => $this->resolveWishlistImage($p),
                ];
            });

        return response()->json([
            'success' => true,
            'user_id' => (int) $userId,
            'items' => $items,
            'count' => $items->count(),
        ]);
    }
}

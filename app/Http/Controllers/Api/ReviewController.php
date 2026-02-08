<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function forProduct($productId)
    {
        $reviews = Review::where('product_id', $productId)
            ->where('is_approved', true)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $reviews->items(),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $productId = (int) $request->product_id;
        $verified = OrderItem::where('product_id', $productId)
            ->whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('payment_status', 'paid');
            })
            ->exists();

        $review = Review::create([
            'product_id' => $productId,
            'user_id' => $user->id,
            'rating' => (int) $request->rating,
            'comment' => $request->comment,
            'is_verified_purchase' => $verified,
            'is_approved' => false,
        ]);

        return response()->json([
            'success' => true,
            'data' => $review->fresh(),
        ], 201);
    }
}

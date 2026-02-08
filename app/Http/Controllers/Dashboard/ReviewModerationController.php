<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewModerationController extends Controller
{
    public function index()
    {
        $pending = Review::where('is_approved', false)
            ->with(['product', 'user'])
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $pending->items(),
            'pagination' => [
                'current_page' => $pending->currentPage(),
                'last_page' => $pending->lastPage(),
                'per_page' => $pending->perPage(),
                'total' => $pending->total(),
            ],
        ]);
    }

    public function approve(Request $request, Review $review)
    {
        $review->is_approved = true;
        $review->rejection_reason = null;
        $review->save();

        $this->recalculateProductRatings($review->product_id);

        return response()->json(['success' => true, 'data' => $review->fresh()]);
    }

    public function reject(Request $request, Review $review)
    {
        $reason = $request->get('reason');
        $review->is_approved = false;
        $review->rejection_reason = $reason;
        $review->save();

        return response()->json(['success' => true, 'data' => $review->fresh()]);
    }

    private function recalculateProductRatings(int $productId): void
    {
        $avg = Review::where('product_id', $productId)->where('is_approved', true)->avg('rating') ?? 0;
        $count = Review::where('product_id', $productId)->where('is_approved', true)->count();

        $product = Product::find($productId);
        if ($product) {
            $product->rating = (int) round($avg);
            $product->reviews_count = (int) $count;
            $product->save();
        }

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('product_performance_metrics')) {
                $key = [
                    'product_id' => $productId,
                    'metric_date' => now()->toDateString(),
                ];
                $existing = DB::table('product_performance_metrics')->where($key)->first();
                if ($existing) {
                    DB::table('product_performance_metrics')->where('id', $existing->id)->update([
                        'average_rating' => $avg ?: null,
                        'review_count' => $count,
                        'updated_at' => now(),
                    ]);
                } else {
                    DB::table('product_performance_metrics')->insert([
                        'product_id' => $productId,
                        'metric_date' => now()->toDateString(),
                        'views' => 0,
                        'cart_additions' => 0,
                        'purchases' => 0,
                        'conversion_rate' => 0,
                        'revenue' => 0,
                        'average_rating' => $avg ?: null,
                        'review_count' => $count,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } catch (\Throwable $e) {
        }
    }
}

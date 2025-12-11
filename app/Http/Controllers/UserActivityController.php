<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserActivityController extends Controller
{
    public function track(Request $request)
    {
        $sessionId = Session::getId();
        $userId = auth()->id();
        
        $validated = $request->validate([
            'activity_type' => 'required|string',
            'product_id' => 'nullable|integer',
            'category_id' => 'nullable|integer',
            'search_query' => 'nullable|string',
            'metadata' => 'nullable|array'
        ]);
        
        // Store activity
        DB::table('user_activity')->insert([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'activity_type' => $validated['activity_type'],
            'product_id' => $validated['product_id'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'search_query' => $validated['search_query'] ?? null,
            'metadata' => isset($validated['metadata']) ? json_encode($validated['metadata']) : null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Update preferences
        $this->updatePreferences($sessionId, $userId, $validated);
        
        return response()->json(['success' => true]);
    }
    
    public function getRecommendations(Request $request)
    {
        $sessionId = Session::getId();
        $userId = auth()->id();
        
        // Get user preferences
        $preferences = DB::table('user_preferences')
            ->where('session_id', $sessionId)
            ->orWhere('user_id', $userId)
            ->first();
        
        if (!$preferences) {
            return response()->json([
                'personalized_products' => [],
                'recommended_categories' => [],
                'search_suggestions' => []
            ]);
        }
        
        $favoriteCategories = json_decode($preferences->favorite_categories ?? '[]', true);
        $searchKeywords = json_decode($preferences->search_keywords ?? '[]', true);
        
        // Get personalized products
        $products = DB::table('products')
            ->when(!empty($favoriteCategories), function($query) use ($favoriteCategories) {
                return $query->whereIn('category_id', array_keys($favoriteCategories));
            })
            ->where('status', 'active')
            ->limit(20)
            ->get();
        
        return response()->json([
            'personalized_products' => $products,
            'recommended_categories' => $favoriteCategories,
            'search_suggestions' => array_slice($searchKeywords, 0, 5),
            'activity_score' => $preferences->activity_score
        ]);
    }
    
    private function updatePreferences($sessionId, $userId, $activity)
    {
        $preferences = DB::table('user_preferences')
            ->where('session_id', $sessionId)
            ->first();
        
        if (!$preferences) {
            $preferences = (object)[
                'favorite_categories' => '{}',
                'search_keywords' => '[]',
                'viewed_products' => '[]',
                'purchased_products' => '[]',
                'activity_score' => 0
            ];
        }
        
        $favoriteCategories = json_decode($preferences->favorite_categories, true) ?: [];
        $searchKeywords = json_decode($preferences->search_keywords, true) ?: [];
        $viewedProducts = json_decode($preferences->viewed_products, true) ?: [];
        $purchasedProducts = json_decode($preferences->purchased_products, true) ?: [];
        $activityScore = $preferences->activity_score ?? 0;
        
        // Update based on activity type
        if ($activity['activity_type'] === 'view' && isset($activity['category_id'])) {
            $categoryId = $activity['category_id'];
            $favoriteCategories[$categoryId] = ($favoriteCategories[$categoryId] ?? 0) + 1;
            $activityScore += 1;
        }
        
        if ($activity['activity_type'] === 'search' && isset($activity['search_query'])) {
            $keyword = strtolower($activity['search_query']);
            if (!in_array($keyword, $searchKeywords)) {
                array_unshift($searchKeywords, $keyword);
                $searchKeywords = array_slice($searchKeywords, 0, 20);
            }
            $activityScore += 2;
        }
        
        if ($activity['activity_type'] === 'view' && isset($activity['product_id'])) {
            if (!in_array($activity['product_id'], $viewedProducts)) {
                array_unshift($viewedProducts, $activity['product_id']);
                $viewedProducts = array_slice($viewedProducts, 0, 50);
            }
            $activityScore += 1;
        }
        
        if ($activity['activity_type'] === 'cart_add' && isset($activity['product_id'])) {
            $activityScore += 5;
        }
        
        if ($activity['activity_type'] === 'purchase' && isset($activity['product_id'])) {
            if (!in_array($activity['product_id'], $purchasedProducts)) {
                array_unshift($purchasedProducts, $activity['product_id']);
                $purchasedProducts = array_slice($purchasedProducts, 0, 30);
            }
            $activityScore += 10;
        }
        
        // Sort categories by frequency
        arsort($favoriteCategories);
        
        DB::table('user_preferences')->updateOrInsert(
            ['session_id' => $sessionId],
            [
                'user_id' => $userId,
                'favorite_categories' => json_encode($favoriteCategories),
                'search_keywords' => json_encode($searchKeywords),
                'viewed_products' => json_encode($viewedProducts),
                'purchased_products' => json_encode($purchasedProducts),
                'activity_score' => $activityScore,
                'last_activity' => now(),
                'updated_at' => now()
            ]
        );
    }
}

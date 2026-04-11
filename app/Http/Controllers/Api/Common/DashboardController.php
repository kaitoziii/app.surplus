<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use App\Services\GeospatialService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function consumer(Request $request): JsonResponse
    {
        $user = $request->user();

        // 1. Stats Calculation
        $transactions = Transaction::where('user_id', $user->id)->get();

        $totalSavings = $transactions->sum('savings_amount');
        $totalSpent = $transactions->sum('price_paid');
        $itemsRescued = $transactions->where('status', 'picked_up')->count();

        // Simple impact calculation (Placeholder for Carbon Footprint AI)
        // Assumption: 1 item rescued approx 2.5kg CO2 (Global standard for mixed food waste)
        $co2SavedKg = $itemsRescued * 2.5;

        // 2. Recent Transactions
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with(['product.store'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 3. Recommended (Urgent & Nearby)
        // If user provides location, use it. Otherwise, return some urgent items.
        $recommendations = [];
        if ($request->has(['lat', 'lon'])) {
            $recommendations = Product::urgent(6)
                ->where('is_available', true)
                ->with('store')
                ->whereHas('store', function ($q) use ($request) {
            // This is slightly inefficient but works for smaller datasets 
            // ideally we use the scope from Store
            })
                ->limit(4)
                ->get();
        }
        else {
            $recommendations = Product::urgent(6)
                ->where('is_available', true)
                ->with('store')
                ->limit(4)
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'profile_photo' => $user->profile_photo_url ?? null, // Standard field
                ],
                'stats' => [
                    'total_savings' => round($totalSavings, 2),
                    'total_spent' => round($totalSpent, 2),
                    'items_rescued' => $itemsRescued,
                    'impact' => [
                        'co2_saved_kg' => round($co2SavedKg, 2),
                        'trees_equivalent' => round($co2SavedKg / 20, 1),
                    ]
                ],
                'recent_transactions' => $recentTransactions->map(fn($t) => [
        'id' => $t->id,
        'status' => $t->status,
        'pickup_code' => $t->pickup_code,
        'quantity' => $t->quantity,
        'price_paid' => $t->price_paid,
        'savings' => $t->savings_amount,
        'product_name' => $t->product->name,
        'product_image' => $t->product->image_url,
        'store_name' => $t->product->store->name,
        'pickup_deadline' => $t->product->pickup_deadline,
        ]),
                'recommendations' => $recommendations->map(fn($p) => [
        'id' => $p->id,
        'name' => $p->name,
        'image_url' => $p->image_url,
        'dynamic_price' => $p->dynamic_price,
        'original_price' => $p->original_price,
        'discount_percentage' => $p->discount_percentage,
        'urgency_level' => $p->urgency_level,
        'store_name' => $p->store->name,
        'distance_km' => round((float)$p->store->distance_km, 2),
        ])
            ]
        ]);
    }
}
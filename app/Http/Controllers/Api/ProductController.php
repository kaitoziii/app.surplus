<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\DynamicDiscountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private DynamicDiscountService $discountService)
    {
    }

    public function byStore(int $storeId): JsonResponse
    {
        $products = Product::where('store_id', $storeId)
            ->available()
            ->with('store')
            ->orderBy('pickup_deadline')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function show(Product $product): JsonResponse
    {
        $breakdown = $this->discountService->getDiscountBreakdown($product);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'original_price' => $product->original_price,
                'dynamic_price' => $product->dynamic_price,
                'discount_percentage' => $product->discount_percentage,
                'urgency_level' => $product->urgency_level,
                'time_remaining_min' => $product->time_remaining_minutes,
                'stock' => $product->stock,
                'unit' => $product->unit,
                'category' => $product->category,
                'pickup_deadline' => $product->pickup_deadline,
                'expiry_date' => $product->expiry_date,
                'is_available' => $product->is_available,
                'store' => $product->store,
                'discount_breakdown' => $breakdown['breakdown'],
            ],
        ]);
    }

    public function urgent(Request $request): JsonResponse
    {
        $hours = (int)($request->query('hours', 3));
        $products = Product::urgent($hours)
            ->where('is_available', true)
            ->with('store')
            ->orderBy('pickup_deadline')
            ->get();

        return response()->json([
            'success' => true,
            'meta' => ['hours_threshold' => $hours],
            'data' => $products,
        ]);
    }
}
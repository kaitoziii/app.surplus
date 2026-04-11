<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\DynamicDiscountService;
use App\Services\GeospatialService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiController extends Controller
{
    public function __construct(
        private DynamicDiscountService $discountService,
        private GeospatialService $geospatialService
        )
    {
    }

    /**
     * Calculate dynamic discount for a hypothetical product.
     */
    public function calculateDiscount(Request $request): JsonResponse
    {
        $request->validate([
            'original_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string',
            'pickup_deadline' => 'required|date_format:Y-m-d H:i:s',
            'created_at' => 'nullable|date_format:Y-m-d H:i:s',
        ]);

        // Create a dummy product instance for calculation
        $product = new Product();
        $product->original_price = (float)$request->original_price;
        $product->stock = (int)$request->stock;
        $product->category = $request->category ?? 'general';
        $product->pickup_deadline = $request->pickup_deadline;

        if ($request->has('created_at')) {
            $product->created_at = $request->created_at;
        }

        $breakdown = $this->discountService->getDiscountBreakdown($product);

        return response()->json([
            'success' => true,
            'data' => $breakdown
        ]);
    }

    /**
     * Get path to AI implementations (surplus-api specific).
     */
    public function getAiPaths(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'dynamic_discounting' => [
                    'name' => 'Dynamic Discounting AI',
                    'path' => 'app/Services/DynamicDiscountService.php',
                    'description' => 'Calculates real-time discounts based on time urgency, stock levels, and food categories.'
                ],
                'geospatial_search' => [
                    'name' => 'Geo-Discovery Engine',
                    'path' => 'app/Services/GeospatialService.php',
                    'description' => 'Handles nearby store discovery using the Haversine formula for spherical distance calculation.'
                ]
            ]
        ]);
    }
}
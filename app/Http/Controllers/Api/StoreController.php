<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\GeospatialService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct(private GeospatialService $geospatial)
    {
    }

    public function nearby(Request $request): JsonResponse
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lon' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:0.1|max:100',
            'category' => 'nullable|string|max:50',
        ]);

        $latitude = (float)$request->lat;
        $longitude = (float)$request->lon;
        $radius = (float)($request->radius ?? 10);

        $query = Store::withinRadius($latitude, $longitude, $radius)
            ->where('is_open', true)
            ->with(['availableProducts' => function ($q) {
            $q->limit(3);
        }]);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $stores = $query->get()->map(function ($store) {
            return [
            'id' => $store->id,
            'name' => $store->name,
            'address' => $store->address,
            'category' => $store->category,
            'is_open' => $store->is_open,
            'distance_km' => round((float)$store->distance_km, 2),
            'distance_label' => app(GeospatialService::class)->formatDistance((float)$store->distance_km),
            'available_count' => $store->availableProducts->count(),
            'products_preview' => $store->availableProducts->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'original_price' => $p->original_price,
            'dynamic_price' => $p->dynamic_price,
            'discount_percentage' => $p->discount_percentage,
            'urgency_level' => $p->urgency_level,
            'stock' => $p->stock,
            ]),
            ];
        });

        return response()->json([
            'success' => true,
            'meta' => [
                'center' => ['lat' => $latitude, 'lon' => $longitude],
                'radius_km' => $radius,
                'total_found' => $stores->count(),
            ],
            'data' => $stores,
        ]);
    }

    public function show(Store $store): JsonResponse
    {
        $store->load('availableProducts');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $store->id,
                'name' => $store->name,
                'address' => $store->address,
                'category' => $store->category,
                'phone' => $store->phone,
                'is_open' => $store->is_open,
                'latitude' => $store->latitude,
                'longitude' => $store->longitude,
                'description' => $store->description,
                'products' => $store->availableProducts,
            ],
        ]);
    }
}
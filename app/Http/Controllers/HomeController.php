<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $stores = Store::where('status', 'approved')
            ->withCount(['products as active_products_count' => function ($q) {
                $q->where('is_available', true)
                  ->where('pickup_deadline', '>', now())
                  ->where('stock', '>', 0);
            }])
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->latest()
            ->get();

        $urgentProducts = Product::with('store')
            ->where('is_available', true)
            ->where('pickup_deadline', '>', now())
            ->where('stock', '>', 0)
            ->where('pickup_deadline', '<=', now()->addHours(3))
            ->latest()
            ->take(8)
            ->get();

        $latestProducts = Product::with('store')
            ->where('is_available', true)
            ->where('pickup_deadline', '>', now())
            ->where('stock', '>', 0)
            ->latest()
            ->take(12)
            ->get();

        return view('home', compact('stores', 'urgentProducts', 'latestProducts', 'search'));
    }

    public function storeDetail($id)
    {
        $store = Store::where('id', $id)
            ->where('status', 'approved')
            ->firstOrFail();

        $products = Product::where('store_id', $id)
            ->where('is_available', true)
            ->where('pickup_deadline', '>', now())
            ->where('stock', '>', 0)
            ->latest()
            ->get();

        return view('store-detail', compact('store', 'products'));
    }
}
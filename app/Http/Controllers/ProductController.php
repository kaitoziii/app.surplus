<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductController extends Controller
{
    // ===============================
    // DASHBOARD MERCHANT
    // ===============================
    public function index()
    {
        $products = Product::all();

        $totalProducts = $products->count();

        $criticalCount = $products->where('urgency_level', 'critical')->count();

        $totalDiscount = $products->sum(function ($p) {
            return ($p->original_price - ($p->final_price ?? $p->original_price));
        });

        return view('products.index', compact(
            'products',
            'totalProducts',
            'criticalCount',
            'totalDiscount'
        ));
    }

    // ===============================
    // CREATE
    // ===============================
    public function create()
    {
        return view('products.create');
    }

    // ===============================
    // STORE
    // ===============================
    public function store(Request $request)
    {
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $expiry = Carbon::parse($request->expiry_date);
        $daysLeft = Carbon::now()->diffInDays($expiry, false);

        if ($daysLeft <= 1) {
            $urgency = 'critical';
            $discount = 50;
        } elseif ($daysLeft <= 3) {
            $urgency = 'high';
            $discount = 30;
        } elseif ($daysLeft <= 5) {
            $urgency = 'medium';
            $discount = 15;
        } else {
            $urgency = 'low';
            $discount = 0;
        }

        $finalPrice = $request->original_price - ($request->original_price * $discount / 100);

        Product::create([
            'store_id' => 1, // sementara
            'name' => $request->name,
            'description' => $request->description,
            'original_price' => $request->original_price,
            'final_price' => $finalPrice,
            'discount_percentage' => $discount,
            'stock' => $request->stock,
            'unit' => $request->unit,
            'category' => $request->category,
            'expiry_date' => $request->expiry_date,
            'pickup_deadline' => $request->pickup_deadline,
            'urgency_level' => $urgency,
            'image_url' => $imagePath,
            'is_available' => true,
        ]);

        return redirect()->route('products.index');
    }

    // ===============================
    // DETAIL PRODUK (CONSUMER)
    // ===============================
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product.detail-product', compact('product'));
    }
}
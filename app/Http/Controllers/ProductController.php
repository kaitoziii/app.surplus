<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        // STATISTIK MERCHANT
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

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $imagePath = null;

        // Upload gambar
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // HITUNG EXPIRY
        $expiry = Carbon::parse($request->expiry_date);
        $daysLeft = Carbon::now()->diffInDays($expiry, false);

        // AUTO URGENCY + DISKON
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

        // HITUNG HARGA AKHIR
        $finalPrice = $request->original_price - ($request->original_price * $discount / 100);

        // SIMPAN DATA
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
}
=======
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
    return "HALAMAN PRODUK";
}
}
>>>>>>> origin/main

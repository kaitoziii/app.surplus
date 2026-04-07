<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index() {
        return "HALAMAN PRODUK";
    }

    // 🔥 TAMBAHKAN INI
    public function show($id)
    {
        $product = Product::first();
        return view('product.detail-product', compact('product'));
    }
}
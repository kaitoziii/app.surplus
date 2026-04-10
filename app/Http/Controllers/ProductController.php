<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index() {
        return "HALAMAN PRODUK";
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product.detail-product', compact('product'));
    }
}
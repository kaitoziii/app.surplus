<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    // 🛒 1. Tambah ke cart
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('product_id', $request->input('product_id'))->first();

        if ($cart) {
            $cart->quantity += $request->input('quantity');
            $cart->save();
        } else {
            Cart::create([
                'product_id' => $request->input('product_id'),
                'quantity' => $request->input('quantity')
            ]);
        }

        // kalau dari web (blade)
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Added to cart']);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang');
    }

    // 📦 2. Lihat semua cart
    public function index()
    {
        return Cart::all();
    }

    // 🔢 3. Update quantity
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::findOrFail($id);
        $cart->quantity = $request->input('quantity');
        $cart->save();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Updated']);
        }

        return back()->with('success', 'Quantity diupdate');
    }

    // ❌ 4. Hapus item
    public function delete($id)
    {
        Cart::destroy($id);

        return response()->json(['message' => 'Deleted']);
    }
}
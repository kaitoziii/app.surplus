<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    // 🛒 ADD TO CART
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $qty = $request->quantity;

        $cart = Cart::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cart) {
            $cart->quantity += $qty;
        } else {
            $cart = new Cart();
            $cart->user_id = auth()->id();
            $cart->product_id = $request->product_id;
            $cart->quantity = $qty;
        }

        $cart->save();

        // 🔥 FIX UTAMA (biar ga redirect ke cart)
        return back()->with('success', 'Berhasil ditambahkan ke keranjang');
    }

    // 📄 VIEW CART
    public function index()
    {
        $cart = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        return view('cart.index', compact('cart'));
    }

    // 🔄 UPDATE QUANTITY
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $cart->quantity = $request->quantity;
        $cart->save();

        return back()->with('success', 'Cart berhasil diupdate');
    }

    // ❌ DELETE ITEM
    public function delete($id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $cart->delete();

        return back()->with('success', 'Item berhasil dihapus');
    }
}
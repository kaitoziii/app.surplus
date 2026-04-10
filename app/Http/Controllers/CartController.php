<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    // 1. Tambah ke cart (WITH STOCK CHECK)
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        // CEK STOCK
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stock tidak cukup!');
        }

        $cart = Cart::where('product_id', $request->product_id)->first();

        if ($cart) {
            $totalQty = $cart->quantity + $request->quantity;

            if ($product->stock < $totalQty) {
                return back()->with('error', 'Stock tidak mencukupi untuk ditambahkan');
            }

            $cart->quantity = $totalQty;
            $cart->save();
        } else {
            Cart::create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        // OPTIONAL (simple stock lock - langsung kurangi stock)
        $product->stock -= $request->quantity;
        $product->save();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Added to cart']);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang');
    }

    // 2. Lihat cart (WEB VIEW)
    public function index()
    {
        $carts = Cart::with('product')->get();

        return view('cart.index', compact('carts'));
    }

    // 3. Update quantity (WITH STOCK RETURN)
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::findOrFail($id);
        $product = Product::findOrFail($cart->product_id);

        // MENGEMBALIKAN STOCK LAMA
        $product->stock += $cart->quantity;

        // CEK STOCK BARU
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stock tidak cukup');
        }

        // KURANGI LAGI
        $product->stock -= $request->quantity;
        $product->save();

        $cart->quantity = $request->quantity;
        $cart->save();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Updated']);
        }

        return back()->with('success', 'Quantity diupdate');
    }

    // 4. Hapus item (RETURN STOCK)
    public function delete($id)
    {
        $cart = Cart::findOrFail($id);
        $product = Product::findOrFail($cart->product_id);

        // MENGEMBALIKAN STOCK
        $product->stock += $cart->quantity;
        $product->save();

        $cart->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
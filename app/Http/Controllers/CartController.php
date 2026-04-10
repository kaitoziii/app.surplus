<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    // 🛒 Tambah ke cart
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock <= 0) {
            return back()->with('error', 'Stok habis');
        }

        $statusCart = $product->time_remaining_minutes <= 0 ? 'pending_expired' : 'pending';

        $cart = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->whereIn('status', ['pending', 'pending_expired'])
            ->first();

        if ($cart) {
            $cart->quantity += $request->quantity;
            $cart->status = $statusCart;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'status' => $statusCart,
                'expired_at' => now()->addMinutes(15)
            ]);
        }

        return back()->with('success', 'Masuk keranjang');
    }

 
    public function index()
    {
    $this->clearExpiredCart();

    $cart = Cart::with('product.store')
        ->where('user_id', auth()->id())
        ->whereIn('status', ['pending', 'pending_expired'])
        ->get()
        ->filter(fn($item) => $item->product !== null);

    if ($cart->isEmpty()) {
        return view('cart.index', compact('cart'));
    }

    $merchant = $cart->first()->product->store;

   
    $cart = $cart->filter(fn($item) => $item->product->store_id == $merchant->id);

    return view('cart.index', compact('cart', 'merchant'));
    }

   
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('id', $id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'pending_expired'])
            ->firstOrFail();

        $cart->quantity = $request->quantity;
        $cart->save();

        return back()->with('success', 'Cart berhasil diupdate');
    }

    public function delete($id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'pending_expired'])
            ->firstOrFail();

        $cart->delete();

        return back()->with('success', 'Item berhasil dihapus');
    }

    public function clearExpiredCart()
    {
        $expiredCarts = Cart::where('expired_at', '<', now())
            ->whereIn('status', ['pending', 'pending_expired'])
            ->get();

        foreach ($expiredCarts as $cart) {
            $cart->status = 'expired';
            $cart->save();
        }
    }
}
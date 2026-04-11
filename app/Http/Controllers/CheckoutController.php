<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $cartItems = Cart::with('product.store')
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'pending_expired'])
            ->get()
            ->filter(fn($item) => $item->product !== null);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong atau produk sudah tidak tersedia');
        }

        $store = $cartItems->first()->product->store;

        $subtotal = $cartItems->sum(fn($item) =>
            $item->quantity * $item->product->dynamic_price
        );

        $handlingFee = $store->handling_fee ?? 0;
        $totalPayment = $subtotal + $handlingFee;

        return view('checkout.index', compact(
            'cartItems',
            'store',
            'subtotal',
            'handlingFee',
            'totalPayment'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pickup_method' => 'required',
            'payment_method' => 'required',
            'transfer_proof' => 'nullable'
        ]);

        $userId = $userId = Auth::id();

        $cartItems = Cart::with('product')
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Keranjang kosong');
        }

        foreach ($cartItems as $item) {

            $product = $item->product;

            if (!$product) continue;

            // CEK STOK
            if ($product->stock < $item->quantity) {
                return back()->with('error', 'Stok tidak cukup untuk ' . $product->name);
            }

            // 🔥 FIX DI SINI (PENTING BANGET)
            $product->stock -= $item->quantity;
            $product->save();

            // HAPUS ITEM CART
            $item->delete();
        }

        return redirect()->route('checkout.index')
            ->with('success', 'Pesanan berhasil dibuat!');
    }
}
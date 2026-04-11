<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $cartItems = Cart::with('product.store')
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'pending_expired'])
            ->get()
            ->filter(fn($item) => $item->product !== null);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong atau produk sudah tidak tersedia');
        }

        $store       = $cartItems->first()->product->store;
        $subtotal    = $cartItems->sum(fn($i) => $i->quantity * $i->product->dynamic_price);
        $handlingFee = $store->handling_fee ?? 0;
        $totalPayment = $subtotal + $handlingFee;

        return view('checkout.index', compact(
            'cartItems', 'store', 'subtotal', 'handlingFee', 'totalPayment'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pickup_method'  => 'required|in:take_away,delivery',
            'payment_method' => 'required|in:cash,transfer,cod',
        ]);

        $userId = auth()->id();

        $cartItems = Cart::with('product.store')
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'pending_expired'])
            ->get()
            ->filter(fn($item) => $item->product !== null);

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Keranjang kosong');
        }

        // Cek stok semua dulu sebelum proses
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->with('error', 'Stok tidak cukup untuk: ' . $item->product->name);
            }
        }

        DB::transaction(function () use ($cartItems, $userId, $request) {
            foreach ($cartItems as $item) {
                $product      = $item->product;
                $pricePaid    = $product->dynamic_price * $item->quantity;
                $savings      = max(0, ($product->original_price - $product->dynamic_price) * $item->quantity);

                Transaction::create([
                    'user_id'                 => $userId,
                    'product_id'              => $product->id,
                    'quantity'                => $item->quantity,
                    'original_price_snapshot' => $product->original_price,
                    'price_paid'              => $pricePaid,
                    'discount_applied'        => $product->discount_percentage,
                    'savings_amount'          => $savings,
                    'status'                  => 'pending',
                    'pickup_code'             => strtoupper(Str::random(6)),
                    'notes'                   => 'Metode: ' . $request->pickup_method . ' | Bayar: ' . $request->payment_method,
                ]);

                // Kurangi stok
                $product->decrement('stock', $item->quantity);

                // Hapus dari cart
                $item->delete();
            }
        });

        return redirect()->route('my-orders.index')
            ->with('success', 'Pesanan berhasil dibuat! Tunjukkan kode pickup ke merchant. 🎉');
    }
}
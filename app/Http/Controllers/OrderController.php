<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // CONSUMER — pesanan saya
    public function myOrders()
    {
        $orders = Transaction::with(['product.store'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('orders.my-orders', compact('orders'));
    }

    // CONSUMER — riwayat selesai
    public function history()
    {
        $orders = Transaction::with(['product.store'])
            ->where('user_id', auth()->id())
            ->where('status', 'picked_up')
            ->latest()
            ->get();

        return view('orders.history', compact('orders'));
    }

    // MERCHANT — semua order masuk ke store mereka
    public function index()
    {
        $orders = Transaction::with(['product.store', 'user'])
            ->whereHas('product.store', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    // MERCHANT — update status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:confirmed,picked_up,cancelled',
        ]);

        $order = Transaction::findOrFail($id);
        $order->status = $request->status;

        if ($request->status === 'picked_up') {
            $order->picked_up_at = now();
        }

        $order->save();

        return redirect()->back()
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
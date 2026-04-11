<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Transaction::with(['product', 'user'])->latest()->get();

        return view('orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:siap diambil,sedang dikirim,selesai',
        ]);

        $order = Transaction::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->route('orders.index')
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function history()
    {
        $orders = Transaction::with(['product', 'user'])
            ->where('status', 'selesai')
            ->latest()
            ->get();

        return view('orders.history', compact('orders'));
    }

    public function myOrders()
    {
        $orders = Transaction::with(['product.store'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('orders.my-orders', compact('orders'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Transaction::with('product')->latest()->get();

        return view('orders.index', compact('orders'));
    }

    public function updateStatus($id, $status)
    {
        $order = Transaction::findOrFail($id);

        $order->status = $status;
        $order->save();

        return back();
    }
}
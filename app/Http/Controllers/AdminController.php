<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalMerchants  = Store::count();
        $totalConsumers  = User::where('role', 'buyer')->count();
        $pendingMerchants = Store::where('status', 'pending')->count();
        $pendingList     = Store::where('status', 'pending')
                                ->with('user')
                                ->latest()
                                ->take(5)
                                ->get();

        return view('admin.dashboard', compact(
            'totalMerchants',
            'totalConsumers',
            'pendingMerchants',
            'pendingList'
        ));
    }

    public function merchants()
    {
        $merchants = Store::with('user')->latest()->paginate(10);
        return view('admin.merchants', compact('merchants'));
    }

    public function consumers()
    {
        $consumers = User::where('role', 'buyer')->latest()->paginate(10);
        return view('admin.consumers', compact('consumers'));
    }

    public function approveMerchant($id)
    {
        Store::findOrFail($id)->update(['status' => 'approved']);
        return back()->with('success', 'Merchant berhasil disetujui.');
    }

    public function rejectMerchant($id)
    {
        Store::findOrFail($id)->update(['status' => 'rejected']);
        return back()->with('success', 'Merchant berhasil ditolak.');
    }
}
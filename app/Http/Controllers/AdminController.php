<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\Product;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalMerchants   = Store::count();
        $totalConsumers   = User::where('role', 'buyer')->count();
        $pendingMerchants = Store::where('status', 'pending')->count();
        $totalProducts    = Product::count();
        $pendingList      = Store::where('status', 'pending')->with('user')->latest()->take(5)->get();

        $today     = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd   = Carbon::now()->endOfWeek();

        $todayTransactions = Transaction::whereDate('created_at', $today)->count();
        $todayRevenue      = Transaction::whereDate('created_at', $today)
                                ->where('status', '!=', 'cancelled')->sum('price_paid');

        $weekTransactions  = Transaction::whereBetween('created_at', [$weekStart, $weekEnd])->count();
        $weekRevenue       = Transaction::whereBetween('created_at', [$weekStart, $weekEnd])
                                ->where('status', '!=', 'cancelled')->sum('price_paid');

        // Grafik 7 hari
        $last7Days = collect(range(6, 0))->map(function ($daysAgo) {
            $date  = Carbon::today()->subDays($daysAgo);
            $total = Transaction::whereDate('created_at', $date)->count();
            return [
                'date'  => $date->format('d M'),
                'total' => $total,
                'waste' => round($total * 0.3, 1), // estimasi: 1 transaksi = 0.3 kg food waste diselamatkan
            ];
        });

        // AI Insight: dampak lingkungan
        $totalConfirmed      = Transaction::where('status', 'picked_up')->count();
        $estimatedWasteSaved = round($totalConfirmed * 0.3, 1); // kg
        $co2Saved            = round($estimatedWasteSaved * 2.4, 1); // 1 kg food waste = 2.4 kg CO2
        $savedFoodPercent    = $totalMerchants > 0
            ? min(100, round(($totalConfirmed / max($totalMerchants, 1)) * 10))
            : 0;

        return view('admin.dashboard', compact(
            'totalMerchants', 'totalConsumers', 'pendingMerchants',
            'totalProducts', 'pendingList',
            'todayTransactions', 'todayRevenue',
            'weekTransactions', 'weekRevenue',
            'last7Days',
            'estimatedWasteSaved', 'co2Saved', 'savedFoodPercent'
        ));
    }

    public function merchants()
    {
        $merchants = Store::with('user')->latest()->paginate(10);
        return view('admin.merchants', compact('merchants'));
    }

    public function consumers(Request $request)
    {
        $query = User::where('role', 'buyer')->latest();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status === 'restricted') {
            $query->where('is_restricted', true);
        } elseif ($request->status === 'active') {
            $query->where('is_restricted', false);
        }

        $consumers = $query->paginate(10);
        return view('admin.consumers', compact('consumers'));
    }

    public function transactions(Request $request)
    {
        $query = Transaction::with(['user', 'product'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $transactions  = $query->paginate(15);
        $countPickedUp = Transaction::where('status', 'picked_up')->count();
        $countActive   = Transaction::whereIn('status', ['pending', 'confirmed'])->count();
        $countCancelled = Transaction::where('status', 'cancelled')->count();

        return view('admin.transactions', compact(
            'transactions', 'countPickedUp', 'countActive', 'countCancelled'
        ));
    }

    public function approveMerchant($id)
    {
        Store::findOrFail($id)->update([
            'status'           => 'approved',
            'rejection_reason' => null,
        ]);
        return back()->with('success', 'Merchant berhasil disetujui.');
    }

    public function rejectMerchant(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        Store::findOrFail($id)->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Merchant berhasil ditolak.');
    }

    public function showMerchant($id)
    {
        $merchant = Store::with('user')->findOrFail($id);
        return view('admin.merchant-detail', compact('merchant'));
    }
}
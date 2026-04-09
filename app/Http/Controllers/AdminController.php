<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\Product;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $last7Days = collect(range(6, 0))->map(function ($daysAgo) {
            $date  = Carbon::today()->subDays($daysAgo);
            $total = Transaction::whereDate('created_at', $date)->count();
            return [
                'date'  => $date->format('d M'),
                'total' => $total,
                'waste' => round($total * 0.3, 1),
            ];
        });

        $totalConfirmed      = Transaction::where('status', 'picked_up')->count();
        $estimatedWasteSaved = round($totalConfirmed * 0.3, 1);
        $co2Saved            = round($estimatedWasteSaved * 2.4, 1);
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

        $transactions   = $query->paginate(15);
        $countPickedUp  = Transaction::where('status', 'picked_up')->count();
        $countActive    = Transaction::whereIn('status', ['pending', 'confirmed'])->count();
        $countCancelled = Transaction::where('status', 'cancelled')->count();

        return view('admin.transactions', compact(
            'transactions', 'countPickedUp', 'countActive', 'countCancelled'
        ));
    }

    public function exportTransactions(Request $request)
    {
        $query = Transaction::with(['user', 'product'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $transactions = $query->get();
        $filename     = 'laporan-transaksi-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID', 'Consumer', 'Email', 'Produk',
                'Qty', 'Harga Asli', 'Harga Bayar',
                'Diskon (%)', 'Hemat', 'Status',
                'Pickup Code', 'Tanggal',
            ]);

            foreach ($transactions as $trx) {
                fputcsv($file, [
                    $trx->id,
                    $trx->user->name    ?? '-',
                    $trx->user->email   ?? '-',
                    $trx->product->name ?? '-',
                    $trx->quantity,
                    $trx->original_price_snapshot,
                    $trx->price_paid,
                    $trx->discount_applied,
                    $trx->savings_amount,
                    $trx->status,
                    $trx->pickup_code ?? '-',
                    $trx->created_at->format('d M Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportTransactionsPdf(Request $request)
    {
        $query = Transaction::with(['user', 'product'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $transactions   = $query->get();
        $totalRevenue   = $transactions->where('status', '!=', 'cancelled')->sum('price_paid');
        $totalSavings   = $transactions->sum('savings_amount');
        $generatedAt    = now()->format('d M Y, H:i');

        $pdf = Pdf::loadView('admin.pdf.transactions', compact(
            'transactions', 'totalRevenue', 'totalSavings', 'generatedAt'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-transaksi-' . now()->format('Y-m-d') . '.pdf');
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
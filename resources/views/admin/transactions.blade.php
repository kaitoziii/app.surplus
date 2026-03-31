@extends('layouts.admin')

@section('title', 'Riwayat Transaksi')

@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-4 gap-3 mb-5">
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <p class="text-xs text-gray-400 mb-1">Total Transaksi</p>
        <p class="text-2xl font-medium text-gray-800">{{ $transactions->total() }}</p>
        <p class="text-xs text-green-600 mt-1">Semua waktu</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <p class="text-xs text-gray-400 mb-1">Selesai (Picked Up)</p>
        <p class="text-2xl font-medium text-green-600">{{ $countPickedUp }}</p>
        <p class="text-xs text-green-500 mt-1">Berhasil diambil</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <p class="text-xs text-gray-400 mb-1">Pending / Confirmed</p>
        <p class="text-2xl font-medium text-amber-500">{{ $countActive }}</p>
        <p class="text-xs text-amber-400 mt-1">Sedang berjalan</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <p class="text-xs text-gray-400 mb-1">Dibatalkan</p>
        <p class="text-2xl font-medium text-red-500">{{ $countCancelled }}</p>
        <p class="text-xs text-red-400 mt-1">Total dibatalkan</p>
    </div>
</div>

{{-- Filter & Search --}}
<div class="bg-white rounded-xl border border-gray-100 p-4 mb-4 flex items-center gap-3">
    <form method="GET" action="{{ route('admin.transactions') }}" class="flex items-center gap-3 w-full">
        <select name="status" onchange="this.form.submit()"
            class="text-xs border border-gray-200 rounded-lg px-3 py-2 text-gray-600 focus:outline-none focus:border-green-400">
            <option value="">Semua Status</option>
            <option value="pending"    {{ request('status') === 'pending'    ? 'selected' : '' }}>Pending</option>
            <option value="confirmed"  {{ request('status') === 'confirmed'  ? 'selected' : '' }}>Confirmed</option>
            <option value="picked_up"  {{ request('status') === 'picked_up'  ? 'selected' : '' }}>Picked Up</option>
            <option value="cancelled"  {{ request('status') === 'cancelled'  ? 'selected' : '' }}>Cancelled</option>
        </select>
        <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
            class="text-xs border border-gray-200 rounded-lg px-3 py-2 text-gray-600 focus:outline-none focus:border-green-400">
        @if(request('status') || request('date'))
        <a href="{{ route('admin.transactions') }}"
            class="text-xs text-red-400 hover:text-red-600">Reset filter</a>
        @endif
        <div class="ml-auto text-xs text-gray-400">
            Menampilkan {{ $transactions->count() }} dari {{ $transactions->total() }} transaksi
        </div>
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="bg-gray-50 text-gray-400">
                    <th class="text-left px-4 py-3 font-medium">#</th>
                    <th class="text-left px-4 py-3 font-medium">Consumer</th>
                    <th class="text-left px-4 py-3 font-medium">Produk</th>
                    <th class="text-left px-4 py-3 font-medium">Qty</th>
                    <th class="text-left px-4 py-3 font-medium">Harga Asli</th>
                    <th class="text-left px-4 py-3 font-medium">Harga Bayar</th>
                    <th class="text-left px-4 py-3 font-medium">Diskon</th>
                    <th class="text-left px-4 py-3 font-medium">Hemat</th>
                    <th class="text-left px-4 py-3 font-medium">Status</th>
                    <th class="text-left px-4 py-3 font-medium">Pickup Code</th>
                    <th class="text-left px-4 py-3 font-medium">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($transactions as $trx)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-400">{{ $trx->id }}</td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $trx->user->name ?? '-' }}</p>
                        <p class="text-gray-400">{{ $trx->user->email ?? '' }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ $trx->product->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $trx->quantity }}</td>
                    <td class="px-4 py-3 text-gray-500">Rp {{ number_format($trx->original_price_snapshot, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">Rp {{ number_format($trx->price_paid, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded-full">
                            {{ $trx->discount_applied }}%
                        </span>
                    </td>
                    <td class="px-4 py-3 text-green-600 font-medium">
                        Rp {{ number_format($trx->savings_amount, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3">
                        @if($trx->status === 'picked_up')
                            <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded-full">Picked Up</span>
                        @elseif($trx->status === 'confirmed')
                            <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">Confirmed</span>
                        @elseif($trx->status === 'cancelled')
                            <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded-full">Cancelled</span>
                        @else
                            <span class="bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full">Pending</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-mono text-gray-600 bg-gray-50 px-2 py-0.5 rounded">
                            {{ $trx->pickup_code ?? '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-400">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="px-4 py-10 text-center text-gray-400">
                        Belum ada transaksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-50">
        {{ $transactions->withQueryString()->links() }}
    </div>
</div>

@endsection
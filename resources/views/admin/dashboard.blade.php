@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-500 mb-1">Total Merchant</p>
        <p class="text-2xl font-medium text-gray-800">{{ $totalMerchants }}</p>
        <p class="text-xs text-green-600 mt-1">Terdaftar di platform</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-500 mb-1">Total Consumer</p>
        <p class="text-2xl font-medium text-gray-800">{{ $totalConsumers }}</p>
        <p class="text-xs text-green-600 mt-1">Pengguna aktif</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-500 mb-1">Menunggu Verifikasi</p>
        <p class="text-2xl font-medium text-orange-500">{{ $pendingMerchants }}</p>
        <p class="text-xs text-orange-400 mt-1">Merchant belum diverifikasi</p>
    </div>
</div>

{{-- Tabel Merchant Pending --}}
<div class="bg-white rounded-xl border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-sm font-medium text-gray-700">Merchant Menunggu Verifikasi</h2>
        <a href="{{ route('admin.merchants') }}" class="text-xs text-green-600 hover:underline">Lihat semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500">
                    <th class="text-left px-6 py-3 font-medium">Nama Toko</th>
                    <th class="text-left px-6 py-3 font-medium">Pemilik</th>
                    <th class="text-left px-6 py-3 font-medium">Lokasi</th>
                    <th class="text-left px-6 py-3 font-medium">Status</th>
                    <th class="text-left px-6 py-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pendingList as $merchant)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $merchant->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $merchant->user->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $merchant->address ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-orange-50 text-orange-600 text-xs px-2 py-1 rounded-full">Pending</span>
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        <form method="POST" action="{{ route('admin.merchants.approve', $merchant->id) }}">
                            @csrf
                            <button class="text-xs bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700">
                                Setujui
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.merchants.reject', $merchant->id) }}">
                            @csrf
                            <button class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded-lg hover:bg-red-200">
                                Tolak
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">
                        Tidak ada merchant yang menunggu verifikasi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
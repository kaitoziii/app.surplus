@extends('layouts.admin')

@section('title', 'Verifikasi Merchant')

@section('content')

<div class="bg-white rounded-xl border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-sm font-medium text-gray-700">Daftar Semua Merchant</h2>
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
                @forelse($merchants as $merchant)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $merchant->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $merchant->user->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $merchant->address ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($merchant->status === 'approved')
                            <span class="bg-green-50 text-green-700 text-xs px-2 py-1 rounded-full">Disetujui</span>
                        @elseif($merchant->status === 'rejected')
                            <span class="bg-red-50 text-red-600 text-xs px-2 py-1 rounded-full">Ditolak</span>
                        @else
                            <span class="bg-orange-50 text-orange-600 text-xs px-2 py-1 rounded-full">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($merchant->status === 'pending')
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.merchants.approve', $merchant->id) }}">
                                @csrf
                                <button class="text-xs bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700">Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('admin.merchants.reject', $merchant->id) }}">
                                @csrf
                                <button class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded-lg hover:bg-red-200">Tolak</button>
                            </form>
                        </div>
                        @else
                            <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">
                        Belum ada merchant terdaftar
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $merchants->links() }}
    </div>
</div>

@endsection
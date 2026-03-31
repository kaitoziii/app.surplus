@extends('layouts.admin')
@section('title', 'Verifikasi Merchant')
@section('content')

<div class="bg-white rounded-xl border border-gray-100">
    <div class="px-5 py-3.5 border-b border-gray-50">
        <p class="text-xs font-medium text-gray-700">Daftar Semua Merchant</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="bg-gray-50 text-gray-400">
                    <th class="text-left px-5 py-3 font-medium">Nama Toko</th>
                    <th class="text-left px-5 py-3 font-medium">Pemilik</th>
                    <th class="text-left px-5 py-3 font-medium">Lokasi</th>
                    <th class="text-left px-5 py-3 font-medium">SIUP/NIB</th>
                    <th class="text-left px-5 py-3 font-medium">Status</th>
                    <th class="text-left px-5 py-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($merchants as $merchant)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3.5">
                        <p class="font-medium text-gray-800">{{ $merchant->name }}</p>
                        <p class="text-gray-400 mt-0.5">Bergabung {{ $merchant->created_at->format('d M Y') }}</p>
                    </td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $merchant->user->name ?? '-' }}</td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $merchant->address ?? '-' }}</td>
                    <td class="px-5 py-3.5">
                        @if($merchant->siup_nib)
                            <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">
                                {{ $merchant->siup_nib }}
                            </span>
                        @else
                            <span class="text-gray-300">Belum diisi</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        @if($merchant->status === 'approved')
                            <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded-full">Disetujui</span>
                        @elseif($merchant->status === 'rejected')
                            <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded-full">Ditolak</span>
                        @else
                            <span class="bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full">Pending</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.merchants.show', $merchant->id) }}"
                               class="text-xs text-blue-600 hover:underline">Detail</a>

                            @if($merchant->status === 'pending')
                            <form method="POST" action="{{ route('admin.merchants.approve', $merchant->id) }}">
                                @csrf
                                <button class="bg-green-600 text-white text-xs px-2.5 py-1 rounded-lg hover:bg-green-700">
                                    Setujui
                                </button>
                            </form>

                            {{-- Tombol Tolak dengan Modal --}}
                            <div x-data="{ open: false }">
                                <button @click="open = true"
                                    class="bg-red-50 text-red-600 text-xs px-2.5 py-1 rounded-lg hover:bg-red-100">
                                    Tolak
                                </button>

                                {{-- Modal --}}
                                <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center"
                                     style="background:rgba(0,0,0,0.3)">
                                    <div class="bg-white rounded-xl border border-gray-100 p-5 w-96 shadow-sm">
                                        <p class="text-sm font-medium text-gray-800 mb-1">Tolak Merchant</p>
                                        <p class="text-xs text-gray-400 mb-3">
                                            Berikan alasan penolakan untuk <strong>{{ $merchant->name }}</strong>
                                        </p>
                                        <form method="POST" action="{{ route('admin.merchants.reject', $merchant->id) }}">
                                            @csrf
                                            <textarea name="rejection_reason" rows="3" required
                                                placeholder="Contoh: Dokumen SIUP/NIB tidak valid atau tidak terbaca..."
                                                class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-red-300 resize-none mb-3"></textarea>
                                            <div class="flex gap-2 justify-end">
                                                <button type="button" @click="open = false"
                                                    class="text-xs px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50">
                                                    Batal
                                                </button>
                                                <button type="submit"
                                                    class="text-xs px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700">
                                                    Konfirmasi Tolak
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-gray-400 text-xs">
                        Belum ada merchant terdaftar
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-50">
        {{ $merchants->links() }}
    </div>
</div>
@endsection
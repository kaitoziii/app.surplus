@extends('layouts.admin')

@section('title', 'Verifikasi Merchant')

@section('content')

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
    <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
        <div>
            <p class="text-sm font-bold text-gray-800">Daftar Semua Merchant</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ $merchants->total() }} merchant terdaftar</p>
        </div>
        @php $pendingCount = $merchants->where('status', 'pending')->count(); @endphp
        @if($pendingCount > 0)
        <span class="text-xs px-3 py-1.5 rounded-full font-medium border"
              style="background:#fef9ec; color:#92650a; border-color:#fde8a0">
            {{ $pendingCount }} menunggu verifikasi
        </span>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-xs min-w-[700px]">
            <thead>
                <tr style="background:#f9f7f4">
                    <th class="text-left px-6 py-3.5 font-semibold text-gray-500">Nama Toko</th>
                    <th class="text-left px-6 py-3.5 font-semibold text-gray-500">Pemilik</th>
                    <th class="text-left px-6 py-3.5 font-semibold text-gray-500 hidden md:table-cell">Lokasi</th>
                    <th class="text-left px-6 py-3.5 font-semibold text-gray-500 hidden lg:table-cell">SIUP/NIB</th>
                    <th class="text-left px-6 py-3.5 font-semibold text-gray-500">Status</th>
                    <th class="text-left px-6 py-3.5 font-semibold text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($merchants as $merchant)
                <tr class="hover:bg-gray-50/50 transition-colors">

                    {{-- Nama Toko --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 font-bold text-sm"
                                 style="background:#edf3e8; color:#33432B">
                                {{ strtoupper(substr($merchant->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $merchant->name }}</p>
                                <p class="text-gray-400 text-[11px] mt-0.5">{{ $merchant->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Pemilik --}}
                    <td class="px-6 py-4 text-gray-600">{{ $merchant->user->name ?? '-' }}</td>

                    {{-- Lokasi --}}
                    <td class="px-6 py-4 text-gray-500 hidden md:table-cell max-w-[160px]">
                        <span class="line-clamp-2">{{ $merchant->address ?? '-' }}</span>
                    </td>

                    {{-- SIUP/NIB --}}
                    <td class="px-6 py-4 hidden lg:table-cell">
                        @if($merchant->siup_nib)
                            <span class="text-xs px-2 py-1 rounded-lg font-medium"
                                  style="background:#f5ede0; color:#6A784D">
                                {{ $merchant->siup_nib }}
                            </span>
                        @else
                            <span class="text-gray-300 text-xs italic">Belum diisi</span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4">
                        @if($merchant->status === 'approved')
                            <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full font-medium badge-approved">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:#6A784D"></span>
                                Disetujui
                            </span>
                        @elseif($merchant->status === 'rejected')
                            <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full font-medium badge-rejected">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:#C4866D"></span>
                                Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full font-medium badge-pending">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:#d4a017; animation: pulse 2s infinite"></span>
                                Pending
                            </span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <a href="{{ route('admin.merchants.show', $merchant->id) }}"
                               class="btn-detail text-xs px-3 py-1.5 rounded-lg font-medium whitespace-nowrap">
                                Detail
                            </a>
                            @if($merchant->status === 'pending')
                            <form method="POST" action="{{ route('admin.merchants.approve', $merchant->id) }}">
                                @csrf
                                <button class="btn-approve text-xs px-3 py-1.5 rounded-lg font-medium whitespace-nowrap shadow-sm">
                                    ✓ Setujui
                                </button>
                            </form>
                            <div x-data="{ open: false }">
                                <button @click="open = true"
                                    class="btn-reject text-xs px-3 py-1.5 rounded-lg font-medium whitespace-nowrap">
                                    ✕ Tolak
                                </button>
                                {{-- Modal Tolak --}}
                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="fixed inset-0 z-50 flex items-center justify-center px-4"
                                     style="background:rgba(32,40,8,0.45)">
                                    <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl">

                                        {{-- Modal Header --}}
                                        <div class="flex items-start gap-3 mb-5">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                                                 style="background:var(--copper-light)">
                                                <svg class="w-5 h-5" style="color:var(--copper)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-800">Tolak Merchant</p>
                                                <p class="text-xs text-gray-400 mt-0.5">
                                                    Berikan alasan untuk <strong class="text-gray-600">{{ $merchant->name }}</strong>
                                                </p>
                                            </div>
                                        </div>

                                        <form method="POST" action="{{ route('admin.merchants.reject', $merchant->id) }}">
                                            @csrf
                                            <label class="text-xs font-semibold text-gray-600 block mb-1.5">
                                                Alasan Penolakan <span style="color:var(--copper)">*</span>
                                            </label>
                                            <textarea name="rejection_reason" rows="3" required
                                                placeholder="Contoh: Dokumen SIUP/NIB tidak valid atau tidak terbaca..."
                                                class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-gray-300 resize-none mb-4 leading-relaxed">
                                            </textarea>
                                            <div class="flex gap-2">
                                                <button type="button" @click="open = false"
                                                    class="flex-1 text-xs py-2.5 rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 font-medium transition-colors">
                                                    Batal
                                                </button>
                                                <button type="submit"
                                                    class="flex-1 text-xs py-2.5 rounded-xl font-semibold text-white shadow-sm transition-opacity hover:opacity-90"
                                                    style="background: linear-gradient(135deg, #C4866D, #a86b54)">
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
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center"
                                 style="background:#edf3e8">
                                <svg class="w-7 h-7" style="color:#6A784D" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-600">Belum ada merchant</p>
                            <p class="text-xs text-gray-400">Merchant yang mendaftar akan muncul di sini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-50">
        {{ $merchants->links() }}
    </div>
</div>

@endsection
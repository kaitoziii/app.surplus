@extends('layouts.admin')
@section('title', 'Detail Merchant')
@section('content')

<div class="mb-4">
    <a href="{{ route('admin.merchants') }}" class="text-xs text-green-600 hover:underline flex items-center gap-1">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke daftar merchant
    </a>
</div>

<div class="grid grid-cols-2 gap-4">

    {{-- Info Merchant --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-700 mb-4">Informasi Merchant</p>
        <div class="space-y-3">
            <div>
                <p class="text-xs text-gray-400">Nama Toko</p>
                <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $merchant->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Pemilik</p>
                <p class="text-sm text-gray-700 mt-0.5">{{ $merchant->user->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Email</p>
                <p class="text-sm text-gray-700 mt-0.5">{{ $merchant->user->email ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Alamat</p>
                <p class="text-sm text-gray-700 mt-0.5">{{ $merchant->address ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Koordinat</p>
                <p class="text-sm text-gray-700 mt-0.5 font-mono">
                    {{ $merchant->latitude }}, {{ $merchant->longitude }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Bergabung</p>
                <p class="text-sm text-gray-700 mt-0.5">{{ $merchant->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Status & Dokumen --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-xs font-medium text-gray-700 mb-4">Status Verifikasi</p>

            <div class="mb-3">
                @if($merchant->status === 'approved')
                    <span class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-medium">✓ Disetujui</span>
                @elseif($merchant->status === 'rejected')
                    <span class="bg-red-50 text-red-600 px-3 py-1 rounded-full text-xs font-medium">✕ Ditolak</span>
                @else
                    <span class="bg-amber-50 text-amber-600 px-3 py-1 rounded-full text-xs font-medium">⏳ Menunggu Verifikasi</span>
                @endif
            </div>

            @if($merchant->rejection_reason)
            <div class="bg-red-50 border border-red-100 rounded-lg p-3 mt-2">
                <p class="text-xs text-red-500 font-medium mb-1">Alasan Penolakan:</p>
                <p class="text-xs text-red-600">{{ $merchant->rejection_reason }}</p>
            </div>
            @endif

            @if($merchant->status === 'pending')
            <div class="flex gap-2 mt-4">
                <form method="POST" action="{{ route('admin.merchants.approve', $merchant->id) }}">
                    @csrf
                    <button class="bg-green-600 text-white text-xs px-4 py-2 rounded-lg hover:bg-green-700">
                        Setujui Merchant
                    </button>
                </form>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-xs font-medium text-gray-700 mb-4">Dokumen SIUP/NIB</p>

            @if($merchant->siup_nib)
            <div class="mb-3">
                <p class="text-xs text-gray-400">Nomor SIUP/NIB</p>
                <p class="text-sm font-mono text-gray-800 mt-0.5 bg-gray-50 px-3 py-1.5 rounded-lg inline-block">
                    {{ $merchant->siup_nib }}
                </p>
            </div>
            @endif

            @if($merchant->siup_nib_file)
            <div>
                <p class="text-xs text-gray-400 mb-2">File Dokumen</p>
                <a href="{{ Storage::url($merchant->siup_nib_file) }}" target="_blank"
                   class="flex items-center gap-2 bg-blue-50 text-blue-600 text-xs px-3 py-2 rounded-lg hover:bg-blue-100 w-fit">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"/>
                    </svg>
                    Lihat / Download Dokumen
                </a>
            </div>
            @else
            <p class="text-xs text-gray-300 italic">Belum ada file dokumen diupload</p>
            @endif
        </div>
    </div>
</div>
@endsection
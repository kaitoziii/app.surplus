@extends('layouts.admin')

@section('title', 'Data Consumer')

@section('content')

{{-- Search & Filter --}}
<div class="bg-white rounded-xl border border-gray-100 p-3.5 mb-4">
    <form method="GET" action="{{ route('admin.consumers') }}" class="flex flex-wrap items-center gap-2">
        <div class="relative flex-1 min-w-[160px]">
            <svg class="w-3.5 h-3.5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama atau email..."
                class="w-full text-xs border border-gray-200 rounded-lg pl-9 pr-3 py-2 focus:outline-none focus:border-green-400">
        </div>
        <select name="status"
            class="text-xs border border-gray-200 rounded-lg px-3 py-2 text-gray-600 focus:outline-none focus:border-green-400">
            <option value="">Semua Status</option>
            <option value="active"      {{ request('status') === 'active'      ? 'selected' : '' }}>Aktif</option>
            <option value="restricted"  {{ request('status') === 'restricted'  ? 'selected' : '' }}>Dibatasi</option>
        </select>
        <button type="submit" class="text-xs bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 whitespace-nowrap">
            Cari
        </button>
        @if(request('search') || request('status'))
        <a href="{{ route('admin.consumers') }}" class="text-xs text-red-400 hover:text-red-600 whitespace-nowrap">Reset</a>
        @endif
        <span class="text-xs text-gray-400 whitespace-nowrap ml-auto">{{ $consumers->total() }} consumer</span>
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full text-xs min-w-[400px]">
            <thead>
                <tr class="bg-gray-50 text-gray-400">
                    <th class="text-left px-4 py-3 font-medium">Nama</th>
                    <th class="text-left px-4 py-3 font-medium">Email</th>
                    <th class="text-left px-4 py-3 font-medium hidden sm:table-cell">No. Telepon</th>
                    <th class="text-left px-4 py-3 font-medium hidden sm:table-cell">Status</th>
                    <th class="text-left px-4 py-3 font-medium hidden md:table-cell">Bergabung</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($consumers as $consumer)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-green-50 border border-green-100 rounded-full flex items-center justify-center text-xs font-medium text-green-700 shrink-0">
                                {{ strtoupper(substr($consumer->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 leading-tight">{{ $consumer->name }}</p>
                                {{-- Status tampil di bawah nama saat mobile --}}
                                <div class="sm:hidden mt-0.5">
                                    @if($consumer->is_restricted)
                                        <span class="bg-red-50 text-red-600 px-1.5 py-0.5 rounded-full text-[10px]">Dibatasi</span>
                                    @else
                                        <span class="bg-green-50 text-green-700 px-1.5 py-0.5 rounded-full text-[10px]">Aktif</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $consumer->email }}</td>
                    <td class="px-4 py-3 text-gray-600 hidden sm:table-cell">{{ $consumer->phone ?? '-' }}</td>
                    <td class="px-4 py-3 hidden sm:table-cell">
                        @if($consumer->is_restricted)
                            <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded-full text-xs">Dibatasi</span>
                        @else
                            <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded-full text-xs">Aktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-400 hidden md:table-cell">{{ $consumer->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-10 text-center text-gray-400 text-xs">
                        @if(request('search') || request('status'))
                            Tidak ada consumer yang cocok
                        @else
                            Belum ada consumer terdaftar
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-50 flex flex-wrap items-center justify-between gap-2">
        <p class="text-xs text-gray-400">
            Menampilkan {{ $consumers->firstItem() ?? 0 }}–{{ $consumers->lastItem() ?? 0 }}
            dari {{ $consumers->total() }} consumer
        </p>
        {{ $consumers->withQueryString()->links() }}
    </div>
</div>

@endsection
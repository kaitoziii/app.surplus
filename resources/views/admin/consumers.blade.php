@extends('layouts.admin')

@section('title', 'Data Consumer')

@section('content')

<div class="bg-white rounded-xl border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-sm font-medium text-gray-700">Daftar Semua Consumer</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500">
                    <th class="text-left px-6 py-3 font-medium">Nama</th>
                    <th class="text-left px-6 py-3 font-medium">Email</th>
                    <th class="text-left px-6 py-3 font-medium">No. Telepon</th>
                    <th class="text-left px-6 py-3 font-medium">Status Akun</th>
                    <th class="text-left px-6 py-3 font-medium">Bergabung</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($consumers as $consumer)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $consumer->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $consumer->email }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $consumer->phone ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($consumer->is_restricted)
                            <span class="bg-red-50 text-red-600 text-xs px-2 py-1 rounded-full">Dibatasi</span>
                        @else
                            <span class="bg-green-50 text-green-700 text-xs px-2 py-1 rounded-full">Aktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $consumer->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">
                        Belum ada consumer terdaftar
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $consumers->links() }}
    </div>
</div>

@endsection
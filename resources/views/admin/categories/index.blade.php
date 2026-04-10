@extends('layouts.admin')
@section('title', 'Kelola Kategori Produk')
@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    <p class="text-xs text-gray-400">Total {{ $categories->count() }} kategori terdaftar</p>
    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
        class="flex items-center gap-1.5 bg-green-600 text-white text-xs px-3.5 py-2 rounded-lg hover:bg-green-700">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kategori
    </button>
</div>

{{-- Stats Row --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
    <div class="bg-white rounded-xl border border-gray-100 p-4 stat-card">
        <p class="text-xs text-gray-400 mb-1">Total Kategori</p>
        <p class="text-2xl font-medium text-gray-800">{{ $categories->count() }}</p>
        <p class="text-xs text-green-600 mt-1">Semua kategori</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 stat-card">
        <p class="text-xs text-gray-400 mb-1">Kategori Aktif</p>
        <p class="text-2xl font-medium text-green-600">{{ $categories->where('is_active', true)->count() }}</p>
        <p class="text-xs text-green-500 mt-1">Sedang aktif</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 stat-card">
        <p class="text-xs text-gray-400 mb-1">Total Produk</p>
        <p class="text-2xl font-medium text-gray-800">{{ $categories->sum('products_count') }}</p>
        <p class="text-xs text-blue-500 mt-1">Di semua kategori</p>
    </div>
</div>

{{-- Category Grid --}}
@if($categories->isEmpty())
<div class="bg-white rounded-xl border border-gray-100 p-12 text-center">
    <div class="text-4xl mb-3">🗂️</div>
    <p class="text-sm font-medium text-gray-600 mb-1">Belum ada kategori</p>
    <p class="text-xs text-gray-400">Tambahkan kategori pertama untuk mengorganisir produk surplus</p>
</div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-5">
    @foreach($categories as $cat)
    @php
        $colors = [
            'green'  => ['bg'=>'bg-green-50','text'=>'text-green-700','border'=>'border-green-100'],
            'blue'   => ['bg'=>'bg-blue-50','text'=>'text-blue-700','border'=>'border-blue-100'],
            'amber'  => ['bg'=>'bg-amber-50','text'=>'text-amber-700','border'=>'border-amber-100'],
            'red'    => ['bg'=>'bg-red-50','text'=>'text-red-700','border'=>'border-red-100'],
            'purple' => ['bg'=>'bg-purple-50','text'=>'text-purple-700','border'=>'border-purple-100'],
            'pink'   => ['bg'=>'bg-pink-50','text'=>'text-pink-700','border'=>'border-pink-100'],
        ];
        $c = $colors[$cat->color] ?? $colors['green'];
    @endphp
    <div class="bg-white rounded-xl border border-gray-100 p-4 hover:border-gray-200 transition-all">
        <div class="flex items-start justify-between mb-3">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 {{ $c['bg'] }} {{ $c['border'] }} border rounded-xl flex items-center justify-center text-lg shrink-0">
                    {{ $cat->icon }}
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $cat->name }}</p>
                    <p class="text-xs text-gray-400">{{ $cat->products_count }} produk</p>
                </div>
            </div>
            @if($cat->is_active)
                <span class="text-xs bg-green-50 text-green-600 px-2 py-0.5 rounded-full shrink-0">Aktif</span>
            @else
                <span class="text-xs bg-gray-100 text-gray-400 px-2 py-0.5 rounded-full shrink-0">Nonaktif</span>
            @endif
        </div>
        <p class="text-xs text-gray-400 mb-4 min-h-[32px] line-clamp-2">{{ $cat->description ?? 'Tidak ada deskripsi' }}</p>
        <div class="flex items-center gap-1.5 pt-3 border-t border-gray-50 flex-wrap">
            <button onclick="openEdit({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description) }}', '{{ $cat->icon }}', '{{ $cat->color }}')"
                class="flex-1 text-xs text-center py-1.5 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 min-w-[60px]">
                Edit
            </button>
            <form method="POST" action="{{ route('admin.categories.toggle', $cat->id) }}" class="flex-1">
                @csrf
                <button class="w-full text-xs text-center py-1.5 rounded-lg border min-w-[80px]
                    {{ $cat->is_active ? 'border-amber-200 text-amber-600 hover:bg-amber-50' : 'border-green-200 text-green-600 hover:bg-green-50' }}">
                    {{ $cat->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
            @if($cat->products_count == 0)
            <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}"
                  onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')">
                @csrf @method('DELETE')
                <button class="text-xs px-2.5 py-1.5 rounded-lg border border-red-100 text-red-400 hover:bg-red-50">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Modal Tambah --}}
<div id="modalTambah" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4" style="background:rgba(0,0,0,0.3)">
    <div class="bg-white rounded-xl border border-gray-100 p-5 w-full max-w-md max-h-screen overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-medium text-gray-800">Tambah Kategori Baru</p>
            <button onclick="document.getElementById('modalTambah').classList.add('hidden')"
                class="text-gray-300 hover:text-gray-500 text-lg leading-none">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="space-y-3">
                <div>
                    <label class="text-xs text-gray-500 mb-1 block">Nama Kategori <span class="text-red-400">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Makanan Berat"
                        class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-green-400">
                </div>
                <div>
                    <label class="text-xs text-gray-500 mb-1 block">Deskripsi</label>
                    <input type="text" name="description" placeholder="Deskripsi singkat kategori"
                        class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-green-400">
                </div>
                <div>
                    <label class="text-xs text-gray-500 mb-2 block">Icon Emoji <span class="text-red-400">*</span></label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['🍱','🥗','🍰','🥐','🍜','🍔','🥩','🍣','🥤','🍫','🥙','🍛'] as $emoji)
                        <label class="cursor-pointer">
                            <input type="radio" name="icon" value="{{ $emoji }}" class="hidden peer" {{ $loop->first ? 'checked' : '' }}>
                            <span class="w-9 h-9 flex items-center justify-center text-lg rounded-lg border border-gray-100 peer-checked:border-green-400 peer-checked:bg-green-50 hover:bg-gray-50">{{ $emoji }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="text-xs text-gray-500 mb-2 block">Warna Badge <span class="text-red-400">*</span></label>
                    <div class="flex gap-2 flex-wrap">
                        @foreach([['green','bg-green-500'],['blue','bg-blue-500'],['amber','bg-amber-500'],['red','bg-red-500'],['purple','bg-purple-500'],['pink','bg-pink-500']] as [$val,$cls])
                        <label class="cursor-pointer">
                            <input type="radio" name="color" value="{{ $val }}" class="hidden peer" {{ $val === 'green' ? 'checked' : '' }}>
                            <span class="w-7 h-7 rounded-full {{ $cls }} flex items-center justify-center peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-{{ $val }}-500"></span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex gap-2 mt-5">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="flex-1 text-xs py-2 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50">Batal</button>
                <button type="submit" class="flex-1 text-xs py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modalEdit" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4" style="background:rgba(0,0,0,0.3)">
    <div class="bg-white rounded-xl border border-gray-100 p-5 w-full max-w-md max-h-screen overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-medium text-gray-800">Edit Kategori</p>
            <button onclick="document.getElementById('modalEdit').classList.add('hidden')"
                class="text-gray-300 hover:text-gray-500 text-lg leading-none">✕</button>
        </div>
        <form method="POST" id="formEdit">
            @csrf @method('PUT')
            <div class="space-y-3">
                <div>
                    <label class="text-xs text-gray-500 mb-1 block">Nama Kategori <span class="text-red-400">*</span></label>
                    <input type="text" name="name" id="editName" required
                        class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-green-400">
                </div>
                <div>
                    <label class="text-xs text-gray-500 mb-1 block">Deskripsi</label>
                    <input type="text" name="description" id="editDesc"
                        class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-green-400">
                </div>
                <div>
                    <label class="text-xs text-gray-500 mb-2 block">Icon Emoji <span class="text-red-400">*</span></label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['🍱','🥗','🍰','🥐','🍜','🍔','🥩','🍣','🥤','🍫','🥙','🍛'] as $emoji)
                        <label class="cursor-pointer">
                            <input type="radio" name="icon" value="{{ $emoji }}" class="hidden peer edit-icon">
                            <span class="w-9 h-9 flex items-center justify-center text-lg rounded-lg border border-gray-100 peer-checked:border-green-400 peer-checked:bg-green-50 hover:bg-gray-50">{{ $emoji }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="text-xs text-gray-500 mb-2 block">Warna Badge <span class="text-red-400">*</span></label>
                    <div class="flex gap-2 flex-wrap">
                        @foreach([['green','bg-green-500'],['blue','bg-blue-500'],['amber','bg-amber-500'],['red','bg-red-500'],['purple','bg-purple-500'],['pink','bg-pink-500']] as [$val,$cls])
                        <label class="cursor-pointer">
                            <input type="radio" name="color" value="{{ $val }}" class="hidden peer edit-color">
                            <span class="w-7 h-7 rounded-full {{ $cls }} flex items-center justify-center peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-{{ $val }}-500"></span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex gap-2 mt-5">
                <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')"
                    class="flex-1 text-xs py-2 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50">Batal</button>
                <button type="submit" class="flex-1 text-xs py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEdit(id, name, desc, icon, color) {
    document.getElementById('editName').value = name;
    document.getElementById('editDesc').value = desc;
    document.getElementById('formEdit').action = '/admin/categories/' + id;
    document.querySelectorAll('.edit-icon').forEach(r => r.checked = r.value === icon);
    document.querySelectorAll('.edit-color').forEach(r => r.checked = r.value === color);
    document.getElementById('modalEdit').classList.remove('hidden');
}
</script>
@endsection
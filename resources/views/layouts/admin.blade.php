<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App.Surplus — Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .fade-in { animation: fadeIn 0.4s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }

        /* Bottom nav hanya di mobile */
        .bottom-nav { padding-bottom: env(safe-area-inset-bottom); }
    </style>
</head>
<body class="bg-gray-50 text-sm">

<div class="flex h-screen overflow-hidden">

    {{-- ===== SIDEBAR DESKTOP (lg ke atas) ===== --}}
    <aside class="hidden lg:flex w-52 bg-white border-r border-gray-100 flex-col shrink-0">
        <div class="px-4 py-4 border-b border-gray-100">
            <span class="text-green-600 font-semibold text-base">App.Surplus</span>
            <span class="ml-2 text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded-full">Admin</span>
        </div>
        <nav class="flex-1 px-3 py-3 space-y-0.5 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors
                      {{ request()->routeIs('admin.dashboard') ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-500 hover:bg-gray-50' }}">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1" stroke-width="2"/><rect x="14" y="3" width="7" height="7" rx="1" stroke-width="2"/><rect x="3" y="14" width="7" height="7" rx="1" stroke-width="2"/><rect x="14" y="14" width="7" height="7" rx="1" stroke-width="2"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.merchants') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors
                      {{ request()->routeIs('admin.merchants*') ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-500 hover:bg-gray-50' }}">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" stroke-width="2"/></svg>
                Verifikasi Merchant
            </a>
            <a href="{{ route('admin.consumers') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors
                      {{ request()->routeIs('admin.consumers') ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-500 hover:bg-gray-50' }}">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke-width="2"/><circle cx="9" cy="7" r="4" stroke-width="2"/></svg>
                Data Consumer
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors
                      {{ request()->routeIs('admin.categories.*') ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-500 hover:bg-gray-50' }}">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z" stroke-width="2"/></svg>
                Kategori Produk
            </a>
            <a href="{{ route('admin.transactions') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs transition-colors
                      {{ request()->routeIs('admin.transactions') ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-500 hover:bg-gray-50' }}">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-width="2"/></svg>
                Riwayat Transaksi
            </a>
        </nav>
        <div class="px-3 py-3 border-t border-gray-100">
            <form method="POST" action="/logout">
                @csrf
                <button class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs text-gray-400 hover:bg-gray-50 w-full transition-colors">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="flex-1 overflow-y-auto min-w-0 flex flex-col">

        {{-- Header --}}
        <header class="bg-white border-b border-gray-100 px-4 lg:px-6 py-3 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h1 class="text-sm font-medium text-gray-800">@yield('title')</h1>
                <p class="text-xs text-gray-400 hidden sm:block">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
            <span class="text-xs bg-green-50 text-green-700 px-2.5 py-1 rounded-full">● Sistem aktif</span>
        </header>

        {{-- Content --}}
        <div class="px-4 lg:px-6 py-5 fade-in pb-24 lg:pb-6 flex-1">

            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="mb-4 flex items-center gap-2.5 bg-green-50 border border-green-200 text-green-700 px-4 py-2.5 rounded-lg text-xs">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="mb-4 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 px-4 py-2.5 rounded-lg text-xs">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

{{-- ===== BOTTOM NAV MOBILE (sembunyikan di lg) ===== --}}
<nav class="bottom-nav fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-100 lg:hidden">
    <div class="flex items-center justify-around px-2 py-2">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-colors
                  {{ request()->routeIs('admin.dashboard') ? 'text-green-600' : 'text-gray-400' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('admin.dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="7" rx="1" stroke-width="1.8"/>
                <rect x="14" y="3" width="7" height="7" rx="1" stroke-width="1.8"/>
                <rect x="3" y="14" width="7" height="7" rx="1" stroke-width="1.8"/>
                <rect x="14" y="14" width="7" height="7" rx="1" stroke-width="1.8"/>
            </svg>
            <span class="text-[10px] font-medium">Dashboard</span>
        </a>

        {{-- Merchant --}}
        <a href="{{ route('admin.merchants') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-colors
                  {{ request()->routeIs('admin.merchants*') ? 'text-green-600' : 'text-gray-400' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ request()->routeIs('admin.merchants*') ? '2.2' : '1.8' }}">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            </svg>
            <span class="text-[10px] font-medium">Merchant</span>
        </a>

        {{-- Consumer --}}
        <a href="{{ route('admin.consumers') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-colors
                  {{ request()->routeIs('admin.consumers') ? 'text-green-600' : 'text-gray-400' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ request()->routeIs('admin.consumers') ? '2.2' : '1.8' }}">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
            </svg>
            <span class="text-[10px] font-medium">Consumer</span>
        </a>

        {{-- Kategori --}}
        <a href="{{ route('admin.categories.index') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-colors
                  {{ request()->routeIs('admin.categories.*') ? 'text-green-600' : 'text-gray-400' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ request()->routeIs('admin.categories.*') ? '2.2' : '1.8' }}">
                <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/>
            </svg>
            <span class="text-[10px] font-medium">Kategori</span>
        </a>

        {{-- Transaksi --}}
        <a href="{{ route('admin.transactions') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-colors
                  {{ request()->routeIs('admin.transactions') ? 'text-green-600' : 'text-gray-400' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ request()->routeIs('admin.transactions') ? '2.2' : '1.8' }}">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="text-[10px] font-medium">Transaksi</span>
        </a>

    </div>
</nav>

</body>
</html>
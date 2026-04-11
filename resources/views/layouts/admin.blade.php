<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App.Surplus — Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        :root {
            --pine:    #202808;
            --kombu:   #33432B;
            --dingley: #6A784D;
            --brandy:  #DEC59E;
            --copper:  #C4866D;
            --brandy-light: #f5ede0;
            --copper-light: #f7ede8;
        }

        .fade-in { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .stat-card { transition: transform 0.15s ease, box-shadow 0.15s ease; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(32,40,8,0.08); }

        .nav-item { transition: all 0.15s ease; }

        /* Sidebar gradient */
        .sidebar-bg {
            background: linear-gradient(180deg, #202808 0%, #33432B 100%);
        }

        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #6A784D; border-radius: 4px; }

        .bottom-nav { padding-bottom: env(safe-area-inset-bottom); }
        tbody tr { transition: background 0.1s ease; }

        /* Badge aksi */
        .btn-approve {
            background: linear-gradient(135deg, #6A784D, #33432B);
            color: white;
            transition: all 0.15s ease;
        }
        .btn-approve:hover { opacity: 0.9; transform: translateY(-1px); }

        .btn-reject {
            background: var(--copper-light);
            color: var(--copper);
            border: 1px solid #e8b9a8;
            transition: all 0.15s ease;
        }
        .btn-reject:hover { background: var(--copper); color: white; }

        .btn-detail {
            background: var(--brandy-light);
            color: var(--kombu);
            border: 1px solid #e8d5b8;
            transition: all 0.15s ease;
        }
        .btn-detail:hover { background: var(--brandy); color: var(--pine); }

        /* Status badges */
        .badge-pending  { background: #fef9ec; color: #92650a; border: 1px solid #fde8a0; }
        .badge-approved { background: #edf3e8; color: #33432B; border: 1px solid #c5d9b0; }
        .badge-rejected { background: var(--copper-light); color: var(--copper); border: 1px solid #e8b9a8; }
        .badge-active   { background: #edf3e8; color: #33432B; border: 1px solid #c5d9b0; }
        .badge-restricted { background: var(--copper-light); color: var(--copper); border: 1px solid #e8b9a8; }
    </style>
</head>
<body class="bg-gray-50 text-sm antialiased">

<div class="flex h-screen overflow-hidden">

    {{-- ===== SIDEBAR DESKTOP ===== --}}
    <aside class="hidden lg:flex w-56 sidebar-bg flex-col shrink-0">

        {{-- Logo --}}
        <div class="px-5 py-5 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-white/15 rounded-xl flex items-center justify-center shrink-0 backdrop-blur">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 3h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2zM5 13h6a2 2 0 012 2v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4a2 2 0 012-2zM17 13h2M17 17h2M17 21h2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-white leading-tight">App.Surplus</p>
                    <p class="text-[10px] text-white/50">Admin Panel</p>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <p class="text-[10px] font-semibold text-white/30 uppercase tracking-wider px-3 mb-3">Menu</p>

            @php $pendingCount = \App\Models\Store::where('status','pending')->count(); @endphp

            <a href="{{ route('admin.dashboard') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs
                      {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 text-white font-semibold' : 'text-white/60 hover:bg-white/8 hover:text-white' }}"
               style="{{ !request()->routeIs('admin.dashboard') ? 'hover:background:rgba(255,255,255,0.08)' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.merchants') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs
                      {{ request()->routeIs('admin.merchants*') ? 'bg-white/15 text-white font-semibold' : 'text-white/60 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Verifikasi Merchant
                @if($pendingCount > 0)
                <span class="ml-auto text-[10px] bg-yellow-400/20 text-yellow-300 border border-yellow-400/30 px-1.5 py-0.5 rounded-full font-semibold">{{ $pendingCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.consumers') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs
                      {{ request()->routeIs('admin.consumers') ? 'bg-white/15 text-white font-semibold' : 'text-white/60 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
                Data Consumer
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs
                      {{ request()->routeIs('admin.categories.*') ? 'bg-white/15 text-white font-semibold' : 'text-white/60 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/>
                </svg>
                Kategori Produk
            </a>

            <a href="{{ route('admin.transactions') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs
                      {{ request()->routeIs('admin.transactions') ? 'bg-white/15 text-white font-semibold' : 'text-white/60 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Riwayat Transaksi
            </a>
        </nav>

        {{-- User & Logout --}}
        <div class="px-3 py-4 border-t border-white/10">
            <div class="flex items-center gap-2.5 px-3 py-2 mb-2">
                <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0" style="background: var(--dingley)">
                    <span class="text-xs font-bold text-white">A</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-white truncate">Admin Surplus</p>
                    <p class="text-[10px] text-white/40 truncate">admin@surplus.com</p>
                </div>
            </div>
            <form method="POST" action="/logout">
                @csrf
                <button class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl text-xs text-white/40 hover:text-white/80 hover:bg-white/8 w-full">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="flex-1 overflow-y-auto min-w-0 flex flex-col">

        {{-- Header --}}
        <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h1 class="text-base font-bold text-gray-900">@yield('title')</h1>
                <p class="text-xs text-gray-400 mt-0.5">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
            <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full font-medium border"
                  style="background:#edf3e8; color:#33432B; border-color:#c5d9b0">
                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:#6A784D"></span>
                Sistem aktif
            </span>
        </header>

        {{-- Content --}}
        <div class="px-6 py-6 fade-in pb-24 lg:pb-6 flex-1">

            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="mb-5 flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-medium border"
                 style="background:#edf3e8; color:#33432B; border-color:#c5d9b0">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="mb-5 flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-medium"
                 style="background:var(--copper-light); color:var(--copper); border:1px solid #e8b9a8">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

{{-- ===== BOTTOM NAV MOBILE ===== --}}
<nav class="bottom-nav fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-100 lg:hidden shadow-lg">
    <div class="flex items-center justify-around px-1 py-2">
        <a href="{{ route('admin.dashboard') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-all"
           style="{{ request()->routeIs('admin.dashboard') ? 'color:#33432B' : 'color:#9ca3af' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('admin.dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                <rect x="14" y="14" width="7" height="7" rx="1.5"/>
            </svg>
            <span class="text-[10px] font-medium">Dashboard</span>
        </a>
        <a href="{{ route('admin.merchants') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl relative"
           style="{{ request()->routeIs('admin.merchants*') ? 'color:#33432B' : 'color:#9ca3af' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ request()->routeIs('admin.merchants*') ? '2.2' : '1.8' }}">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            @if($pendingCount > 0)
            <span class="absolute top-1 right-2 w-2 h-2 rounded-full" style="background:#C4866D"></span>
            @endif
            <span class="text-[10px] font-medium">Merchant</span>
        </a>
        <a href="{{ route('admin.consumers') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl"
           style="{{ request()->routeIs('admin.consumers') ? 'color:#33432B' : 'color:#9ca3af' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ request()->routeIs('admin.consumers') ? '2.2' : '1.8' }}">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
            </svg>
            <span class="text-[10px] font-medium">Consumer</span>
        </a>
        <a href="{{ route('admin.categories.index') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl"
           style="{{ request()->routeIs('admin.categories.*') ? 'color:#33432B' : 'color:#9ca3af' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ request()->routeIs('admin.categories.*') ? '2.2' : '1.8' }}">
                <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/>
            </svg>
            <span class="text-[10px] font-medium">Kategori</span>
        </a>
        <a href="{{ route('admin.transactions') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl"
           style="{{ request()->routeIs('admin.transactions') ? 'color:#33432B' : 'color:#9ca3af' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ request()->routeIs('admin.transactions') ? '2.2' : '1.8' }}">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="text-[10px] font-medium">Transaksi</span>
        </a>
    </div>
</nav>

</body>
</html>
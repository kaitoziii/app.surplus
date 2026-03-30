<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App.Surplus — Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-100 font-sans">

    {{-- Sidebar --}}
    <div class="flex h-screen">
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col">
            <div class="px-6 py-5 border-b border-gray-100">
                <span class="text-green-600 font-semibold text-lg">App.Surplus</span>
                <span class="ml-2 text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Admin</span>
            </div>
            <nav class="flex-1 px-4 py-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                          {{ request()->routeIs('admin.dashboard') ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                </a>
                <a href="{{ route('admin.merchants') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                          {{ request()->routeIs('admin.merchants') ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="store" class="w-4 h-4"></i> Verifikasi Merchant
                </a>
                <a href="{{ route('admin.consumers') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                          {{ request()->routeIs('admin.consumers') ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="users" class="w-4 h-4"></i> Data Consumer
                </a>
            </nav>
            <div class="px-4 py-4 border-t border-gray-100">
                <form method="POST" action="/logout">
                    @csrf
                    <button class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50 w-full">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto">
            <header class="bg-white border-b border-gray-200 px-8 py-4">
                <h1 class="text-base font-medium text-gray-800">@yield('title')</h1>
            </header>
            <div class="px-8 py-6">
                @yield('content')
            </div>
        </main>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
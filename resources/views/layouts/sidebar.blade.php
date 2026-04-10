<div class="w-64 bg-[#0f172a] text-gray-200 min-h-screen flex flex-col justify-between">

    <!-- TOP -->
    <div class="p-5">

        <!-- LOGO -->
        <div class="flex items-center gap-2 mb-8">
            <div class="bg-green-500 p-2 rounded-lg">
                📦
            </div>
            <div>
                <h2 class="font-bold text-lg text-white">App.Surplus</h2>
                <p class="text-xs text-gray-400">Merchant Panel</p>
            </div>
        </div>

        <!-- MENU -->
        <p class="text-xs text-gray-500 mb-3">MENU</p>

        <ul class="space-y-2 text-sm">

            <li>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 transition">
                    📊 Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('products.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-800 text-white">
                    📦 Produk Saya
                </a>
            </li>

            <li>
                <a href="{{ route('products.create') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 transition">
                    ➕ Tambah Produk
                </a>
            </li>

            <li>
                <a href="#"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 transition">
                    📥 Pesanan Masuk
                </a>
            </li>

            <li>
                <a href="#"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 transition">
                    📜 Riwayat Penjualan
                </a>
            </li>

        </ul>
    </div>

    <!-- USER -->
    <div class="p-5 border-t border-slate-800">

        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>

            <div>
                <p class="text-sm font-semibold text-white">
                    {{ Auth::user()->name }}
                </p>
                <p class="text-xs text-gray-400">
                    {{ Auth::user()->email }}
                </p>
            </div>
        </div>

        <!-- LOGOUT -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full text-left text-sm text-gray-400 hover:text-red-400 transition">
                🚪 Logout
            </button>
        </form>

    </div>

</div>
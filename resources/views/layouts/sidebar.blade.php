<div class="w-64 bg-green-900 text-white min-h-screen flex flex-col justify-between shadow-sm">

    <!-- TOP -->
    <div class="p-6">

        <!-- LOGO -->
        <div class="mb-10">
            <h2 class="text-3xl font-bold leading-tight">App.Surplus</h2>
            <p class="text-lg text-green-100 mt-3">Merchant Panel</p>
        </div>

        <!-- MENU -->
        <ul class="space-y-3 text-sm">

            <!-- DASHBOARD -->
            <li>
                <a href="{{ route('dashboard') }}"
                   class="block px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('dashboard') ? 'bg-green-500 text-white font-semibold shadow-sm' : 'text-white hover:bg-green-800' }}">
                    Dashboard
                </a>
            </li>

            <!-- PRODUK -->
            <li>
                <a href="{{ route('products.index') }}"
                   class="block px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('products.index') ? 'bg-green-500 text-white font-semibold shadow-sm' : 'text-white hover:bg-green-800' }}">
                    Produk Saya
                </a>
            </li>

            <!-- TAMBAH PRODUK -->
            <li>
                <a href="{{ route('products.create') }}"
                   class="block px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('products.create') ? 'bg-green-500 text-white font-semibold shadow-sm' : 'text-white hover:bg-green-800' }}">
                    Tambah Produk
                </a>
            </li>

            <!-- PESANAN -->
            <li>
                <a href="{{ route('orders.index') }}"
                   class="block px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('orders.*') ? 'bg-green-500 text-white font-semibold shadow-sm' : 'text-white hover:bg-green-800' }}">
                    Pesanan Masuk
                </a>
            </li>

            <!-- RIWAYAT -->
            <li>
                <a href="{{ route('history.index') }}"
                   class="block px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('history.*') ? 'bg-green-500 text-white font-semibold shadow-sm' : 'text-white hover:bg-green-800' }}">
                    Riwayat Penjualan
                </a>
            </li>

        </ul>
    </div>

    <!-- USER -->
    <div class="p-6 border-t border-green-800">

        <div class="mb-4">
            <p class="text-base font-semibold text-white">
                {{ Auth::user()->name }}
            </p>
            <p class="text-sm text-green-100 break-words">
                {{ Auth::user()->email }}
            </p>
        </div>

        <!-- LOGOUT -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left text-sm text-green-100 hover:text-red-300 transition">
                Logout
            </button>
        </form>

    </div>

</div>
<div class="w-64 bg-green-900 text-white min-h-screen p-4">

    <!-- LOGO -->
    <h2 class="text-xl font-bold mb-6">App.Surplus</h2>

    <p class="text-sm text-gray-300 mb-6">Merchant Panel</p>

    <!-- MENU -->
    <ul class="space-y-3">

        <li>
            <a href="#" class="block px-3 py-2 rounded-lg bg-green-700">
                Dashboard
            </a>
        </li>

        <li>
            <a href="{{ route('products.index') }}" class="block px-3 py-2 hover:bg-green-700 rounded-lg">
                Produk Saya
            </a>
        </li>

        <li>
            <a href="{{ route('products.create') }}" class="block px-3 py-2 hover:bg-green-700 rounded-lg">
                Tambah Produk
            </a>
        </li>

        <li>
            <a href="#" class="block px-3 py-2 hover:bg-green-700 rounded-lg">
                Pesanan Masuk
            </a>
        </li>

        <li>
            <a href="#" class="block px-3 py-2 hover:bg-green-700 rounded-lg">
                Riwayat Penjualan
            </a>
        </li>

    </ul>

    <!-- USER -->
    <div class="mt-10 border-t border-green-700 pt-4 text-sm">
        <p class="font-semibold">Merchant</p>
        <p class="text-gray-300 text-xs">user@email.com</p>
    </div>

</div>
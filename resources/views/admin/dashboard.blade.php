<x-app-layout>

<div class="flex">

    <!-- SIDEBAR -->
    <aside class="w-64">
        @include('layouts.sidebar')
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 bg-gray-100 min-h-screen p-6">

        <div class="max-w-7xl mx-auto">

            <!-- HEADER -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    Dashboard
                </h1>
                <p class="text-sm text-gray-500">
                    {{ now()->format('l, d F Y') }}
                </p>
            </div>

            <!-- STATS -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

                <!-- TOTAL PRODUCT -->
                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-sm text-gray-500">Total Produk</p>
                    <h2 class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $totalProducts ?? 0 }}
                    </h2>
                </div>

                <!-- ORDER TODAY -->
                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
                    <h2 class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $todayOrders ?? 0 }}
                    </h2>
                </div>

                <!-- AI INSIGHT -->
                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-sm text-gray-500">AI Insight</p>
                    <p class="text-sm mt-2 text-gray-700">
                        Produk mendekati expiry meningkat hari ini.
                    </p>
                </div>

                <!-- SAVED RATE -->
                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-sm text-gray-500">Tingkat Penyelamatan</p>
                    <h2 class="text-2xl font-bold text-green-600 mt-1">
                        {{ $rescueRate ?? 0 }}%
                    </h2>
                </div>

            </div>

            <!-- MAIN CONTENT -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- CHART -->
                <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        Transaksi 7 Hari Terakhir
                    </h2>

                    <div class="h-64 flex items-center justify-center text-gray-400">
                        (Chart nanti di sini 📊)
                    </div>
                </div>

                <!-- QUICK INFO -->
                <div class="bg-white p-6 rounded-xl shadow">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        Ringkasan
                    </h2>

                    <ul class="space-y-3 text-sm text-gray-600">
                        <li>• Produk hampir kadaluarsa: <b>{{ $criticalCount ?? 0 }}</b></li>
                        <li>• Total diskon diberikan: <b>Rp {{ number_format($totalDiscount ?? 0) }}</b></li>
                        <li>• Produk aktif: <b>{{ $activeProducts ?? 0 }}</b></li>
                    </ul>
                </div>

            </div>

        </div>

    </main>

</div>

</x-app-layout>
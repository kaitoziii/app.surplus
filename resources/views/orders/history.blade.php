<x-app-layout>
    <main class="flex-1 bg-gray-100 min-h-screen p-8">
        <div class="max-w-[1280px] mx-auto">

            <!-- HEADER -->
            <div class="flex items-start justify-between mb-8">
                <div>
                    <h1 class="text-[26px] font-bold text-gray-900 leading-tight">
                        Riwayat Penjualan
                    </h1>
                    <p class="mt-2 text-[14px] text-gray-500">
                        Daftar pesanan yang telah selesai.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-[18px] font-semibold text-gray-900">
                        Riwayat Pesanan
                    </h2>
                </div>

                @if(isset($orders) && $orders->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">ID</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Customer</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Produk</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Total</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Tanggal</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @foreach($orders as $order)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                        #{{ $order->id }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $order->user->name ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $order->product->name ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        Rp {{ number_format($order->total ?? $order->total_price ?? 0) }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $order->created_at->format('d M Y H:i') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-16 text-center text-gray-400">
                        Belum ada riwayat penjualan.
                    </div>
                @endif
            </div>

        </div>
    </main>
</x-app-layout>
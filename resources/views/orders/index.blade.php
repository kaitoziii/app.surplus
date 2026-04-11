<x-app-layout>
    <main class="flex-1 bg-gray-100 min-h-screen p-8">
        <div class="max-w-[1280px] mx-auto">

            <div class="flex items-start justify-between mb-8">
                <div>
                    <h1 class="text-[26px] font-bold text-gray-900 leading-tight">
                        Pesanan Masuk
                    </h1>
                    <p class="mt-2 text-[14px] text-gray-500">
                        Kelola pesanan pelanggan yang masuk ke merchant.
                    </p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-xl bg-green-100 border border-green-200 px-4 py-3 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-[18px] font-semibold text-gray-900">
                        Daftar Pesanan
                    </h2>
                </div>

                @if(isset($orders) && $orders->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">ID Order</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Customer</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Produk</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Total</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Tanggal</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @foreach($orders as $order)
                                    @php
                                        $status = strtolower($order->status ?? 'disetujui');
                                        $total = $order->total_price ?? $order->total ?? 0;
                                    @endphp

                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                                            #{{ $order->id }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $order->user->name ?? 'Customer' }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $order->product->name ?? '-' }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            Rp {{ number_format($total) }}
                                        </td>

                                        <td class="px-6 py-4">
                                            <span class="
                                                inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                {{ $status === 'disetujui' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                {{ $status === 'siap diambil' ? 'bg-blue-100 text-blue-700' : '' }}
                                                {{ $status === 'sedang dikirim' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                                {{ $status === 'selesai' ? 'bg-green-100 text-green-700' : '' }}
                                            ">
                                                {{ ucfirst($order->status ?? 'Disetujui') }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $order->created_at ? $order->created_at->format('d M Y, H:i') : '-' }}
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-2">
                                                @if($status === 'disetujui')
                                                    <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="siap diambil">
                                                        <button type="submit"
                                                            class="px-3 py-2 rounded-lg bg-blue-500 text-white text-xs font-medium hover:bg-blue-600 transition">
                                                            Siap Diambil
                                                        </button>
                                                    </form>

                                                    <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="sedang dikirim">
                                                        <button type="submit"
                                                            class="px-3 py-2 rounded-lg bg-indigo-500 text-white text-xs font-medium hover:bg-indigo-600 transition">
                                                            Sedang Dikirim
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(in_array($status, ['siap diambil', 'sedang dikirim']))
                                                    <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="selesai">
                                                        <button type="submit"
                                                            class="px-3 py-2 rounded-lg bg-green-500 text-white text-xs font-medium hover:bg-green-600 transition">
                                                            Selesai
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-16 text-center">
                        <p class="text-gray-400 text-[17px]">
                            Belum ada pesanan masuk.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </main>
</x-app-layout>
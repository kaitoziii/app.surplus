<x-app-layout>
    <main class="flex-1 bg-gray-100 min-h-screen p-8">
        <div class="max-w-[1280px] mx-auto">

            <!-- HEADER -->
            <div class="flex items-start justify-between mb-8">
                <div>
                    <h1 class="text-[26px] font-bold text-gray-900 leading-tight">
                        Dashboard Merchant
                    </h1>
                    <p class="mt-2 text-[14px] text-gray-500">
                        {{ \Carbon\Carbon::now()->format('l, d F Y') }}
                    </p>
                </div>

                <a href="{{ route('products.create') }}"
                   class="inline-flex items-center px-6 py-3 rounded-2xl bg-blue-500 text-white font-medium text-[16px] shadow-sm hover:bg-blue-600 transition">
                    + Tambah Produk
                </a>
            </div>

            <!-- STAT CARD -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-2xl px-6 py-5 shadow-sm border border-gray-100">
                    <p class="text-[16px] text-gray-500 mb-2">Total Produk</p>
                    <h2 class="text-[20px] font-bold text-gray-900">{{ $totalProducts }}</h2>
                </div>

                <div class="bg-blue-50 rounded-2xl px-6 py-5 shadow-sm border border-blue-100">
                    <p class="text-[16px] text-gray-500 mb-2">Total Stok</p>
                    <h2 class="text-[20px] font-bold text-blue-600">{{ $products->sum('stock') }}</h2>
                </div>

                <div class="bg-red-50 rounded-2xl px-6 py-5 shadow-sm border border-red-100">
                    <p class="text-[16px] text-gray-500 mb-2">Produk Critical</p>
                    <h2 class="text-[20px] font-bold text-red-500">{{ $criticalCount }}</h2>
                </div>

                <div class="bg-green-50 rounded-2xl px-6 py-5 shadow-sm border border-green-100">
                    <p class="text-[16px] text-gray-500 mb-2">Total Diskon</p>
                    <h2 class="text-[20px] font-bold text-green-600">Rp {{ number_format($totalDiscount) }}</h2>
                </div>
            </div>

            <!-- INSIGHT PRODUK -->
            <div class="bg-white rounded-2xl px-6 py-7 shadow-sm border border-gray-100 mb-6">
                <h3 class="text-[18px] font-semibold text-gray-900 mb-8">
                    Insight Produk
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div>
                        <h4 class="text-[20px] font-bold text-red-500">
                            {{ $criticalCount }}
                        </h4>
                        <p class="mt-1 text-[14px] text-gray-500">
                            Hampir expired
                        </p>
                    </div>

                    <div>
                        <h4 class="text-[20px] font-bold text-gray-900">
                            {{ $totalProducts }}
                        </h4>
                        <p class="mt-1 text-[14px] text-gray-500">
                            Produk aktif
                        </p>
                    </div>

                    <div>
                        <h4 class="text-[20px] font-bold text-green-600">
                            Rp {{ number_format($totalDiscount) }}
                        </h4>
                        <p class="mt-1 text-[14px] text-gray-500">
                            Total diskon
                        </p>
                    </div>
                </div>
            </div>

            <!-- PRODUK PRIORITAS -->
            <div class="bg-white rounded-2xl px-6 py-7 shadow-sm border border-gray-100 mb-6">
                <h3 class="text-[18px] font-semibold text-red-500 mb-5">
                    Produk Prioritas (Segera Dijual)
                </h3>

                @php
                    $priorityProducts = $products->filter(function ($product) {
                        return isset($product->urgency_status) && $product->urgency_status === 'critical';
                    });
                @endphp

                @if($priorityProducts->count())
                    <div class="space-y-4">
                        @foreach($priorityProducts as $product)
                            <div class="flex items-center justify-between border border-red-100 bg-red-50 rounded-xl px-4 py-4">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $product->name }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Stok: {{ $product->stock }}
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="font-semibold text-red-500">
                                        Rp {{ number_format($product->dynamic_price ?? $product->price ?? 0) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-[16px] text-gray-500">
                        Tidak ada produk mendesak
                    </p>
                @endif
            </div>

            <!-- LIST PRODUK / EMPTY STATE -->
            @if($products->count())
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($products as $product)
                        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                            @if(!empty($product->image))
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-44 object-cover rounded-xl mb-4">
                            @endif

                            <h3 class="text-[18px] font-semibold text-gray-900 mb-2">
                                {{ $product->name }}
                            </h3>

                            <p class="text-[15px] text-gray-500 mb-1">
                                Rp {{ number_format($product->dynamic_price ?? $product->price ?? 0) }}
                            </p>

                            <p class="text-[14px] text-gray-400 mb-4">
                                Stock: {{ $product->stock }}
                            </p>

                            <div class="flex gap-2">
                                <a href="{{ route('products.edit', $product->id) }}"
                                   class="px-4 py-2 rounded-lg bg-yellow-400 text-white text-sm font-medium hover:bg-yellow-500 transition">
                                    Edit
                                </a>

                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-4 py-2 rounded-lg bg-red-500 text-white text-sm font-medium hover:bg-red-600 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-400 text-[18px] mt-10">
                    Belum ada produk yang ditambahkan.
                </div>
            @endif

        </div>
    </main>
</x-app-layout>
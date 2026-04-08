<x-app-layout>

<div class="container mx-auto px-4 py-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Dashboard Merchant</h1>
            <p class="text-sm text-gray-500">
                {{ \Carbon\Carbon::now()->format('l, d F Y') }}
            </p>
        </div>

        <a href="{{ route('products.create') }}" 
           class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
           + Tambah Produk
        </a>
    </div>

    <!-- 📊 STATISTICS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-sm text-gray-500">Total Produk</p>
            <h2 class="text-xl font-bold">{{ $totalProducts }}</h2>
        </div>

        <div class="bg-blue-50 p-4 rounded-xl shadow">
            <p class="text-sm text-gray-500">Total Stok</p>
            <h2 class="text-xl font-bold text-blue-600">
                {{ $products->sum('stock') }}
            </h2>
        </div>

        <div class="bg-red-50 p-4 rounded-xl shadow">
            <p class="text-sm text-gray-500">Produk Critical</p>
            <h2 class="text-xl font-bold text-red-600">{{ $criticalCount }}</h2>
        </div>

        <div class="bg-green-50 p-4 rounded-xl shadow">
            <p class="text-sm text-gray-500">Total Diskon</p>
            <h2 class="text-xl font-bold text-green-600">
                Rp {{ number_format($totalDiscount) }}
            </h2>
        </div>

    </div>

    <!-- 🤖 INSIGHT -->
    <div class="bg-white p-6 rounded-xl shadow mb-6">
        <h3 class="font-semibold mb-2">Insight Produk</h3>

        <div class="grid grid-cols-3 gap-4 text-center mt-4">

            <div>
                <h2 class="text-xl font-bold text-red-500">
                    {{ $criticalCount }}
                </h2>
                <p class="text-sm text-gray-500">Hampir expired</p>
            </div>

            <div>
                <h2 class="text-xl font-bold">
                    {{ $products->count() }}
                </h2>
                <p class="text-sm text-gray-500">Produk aktif</p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-green-500">
                    Rp {{ number_format($totalDiscount) }}
                </h2>
                <p class="text-sm text-gray-500">Total diskon</p>
            </div>

        </div>
    </div>

    <!-- 🚨 PRODUK PRIORITAS -->
    <div class="bg-white p-6 rounded-xl shadow mb-6">
        <h3 class="font-semibold mb-4 text-red-600">
            Produk Prioritas (Segera Dijual)
        </h3>

        @forelse($products->where('urgency_level', 'critical') as $product)
            <div class="flex justify-between border-b py-2 text-sm">
                <span>{{ $product->name }}</span>
                <span class="text-red-500 font-semibold">
                    Exp: {{ $product->expiry_date }}
                </span>
            </div>
        @empty
            <p class="text-gray-500">Tidak ada produk mendesak</p>
        @endforelse
    </div>

    <!-- 📦 GRID PRODUK -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        @forelse($products as $product)

        @php
            $urgencyClass = match($product->urgency_level) {
                'critical' => 'bg-red-500 text-white',
                'high' => 'bg-orange-400 text-white',
                'medium' => 'bg-yellow-300 text-black',
                default => 'bg-green-400 text-white',
            };

            $daysLeft = \Carbon\Carbon::now()->diffInDays($product->expiry_date, false);
        @endphp

        <div class="bg-white rounded-2xl shadow hover:shadow-lg transition p-4">

            <!-- IMAGE -->
            <div class="relative">
                @if($product->image_url)
                    <img src="{{ asset('storage/' . $product->image_url) }}" 
                         class="w-full h-40 object-cover rounded-xl">
                @else
                    <div class="w-full h-40 bg-gray-200 rounded-xl flex items-center justify-center text-gray-400">
                        No Image
                    </div>
                @endif

                <!-- BADGE -->
                <span class="absolute top-2 left-2 px-2 py-1 text-xs rounded-full {{ $urgencyClass }}">
                    {{ ucfirst($product->urgency_level) }}
                </span>
            </div>

            <!-- CONTENT -->
            <div class="mt-3">
                <h2 class="font-semibold text-lg">{{ $product->name }}</h2>

                <p class="text-sm text-gray-500 line-clamp-2">
                    {{ $product->description }}
                </p>

                <!-- PRICE -->
                <div class="mt-2">

                    @if($product->discount_percentage > 0)
                        <p class="text-gray-400 line-through text-sm">
                            Rp {{ number_format($product->original_price) }}
                        </p>
                    @endif

                    <p class="text-green-600 font-bold text-lg">
                        Rp {{ number_format($product->final_price ?? $product->original_price) }}
                    </p>

                    @if($product->discount_percentage > 0)
                        <span class="inline-block mt-1 text-xs bg-red-500 text-white px-2 py-1 rounded">
                            -{{ $product->discount_percentage }}%
                        </span>
                    @endif

                </div>

                <!-- INFO -->
                <div class="text-xs text-gray-400 mt-2 space-y-1">
                    <p>Stock: {{ $product->stock }}</p>
                    <p>Deadline: {{ $product->pickup_deadline }}</p>
                </div>

                <!-- ALERT -->
                @if($product->urgency_level == 'critical')
                    <p class="text-xs text-red-600 mt-1 font-semibold">
                        Diskon maksimal karena hampir expired!
                    </p>
                @endif

                @if($daysLeft <= 1)
                    <p class="text-red-500 text-xs mt-2 font-semibold">
                        ⚠ Hampir kadaluarsa!
                    </p>
                @elseif($daysLeft <= 3)
                    <p class="text-orange-500 text-xs mt-2">
                        Segera habiskan ({{ $daysLeft }} hari lagi)
                    </p>
                @endif

                <!-- ACTION -->
                <div class="flex justify-between mt-4">
                    <a href="{{ route('products.edit', $product->id) }}"
                       class="text-sm text-blue-500 hover:underline">
                       Edit
                    </a>

                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="text-sm text-red-500 hover:underline">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

        </div>

        @empty
            <div class="col-span-3 text-center text-gray-400">
                Belum ada produk yang ditambahkan.
            </div>
        @endforelse

    </div>

</div>

</x-app-layout>
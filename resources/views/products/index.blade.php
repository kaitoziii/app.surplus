<x-app-layout>

<div class="flex">

    <!-- SIDEBAR -->
    <aside class="w-64">
        @include('layouts.sidebar')
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 bg-gray-100 min-h-screen p-6">

        <div class="max-w-5xl mx-auto">

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Keranjang Saya</h1>

                <a href="{{ route('products.index') }}"
                   class="text-sm text-blue-500 hover:underline">
                   ← Kembali Belanja
                </a>
            </div>

            <!-- ALERT -->
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- LIST CART -->
            <div class="bg-white rounded-xl shadow">

                @forelse($carts as $cart)

                <div class="flex items-center justify-between border-b p-4">

                    <!-- PRODUCT -->
                    <div class="flex items-center gap-4">

                        <!-- IMAGE -->
                        @if($cart->product->image_url)
                            <img src="{{ asset('storage/' . $cart->product->image_url) }}"
                                 class="w-20 h-20 object-cover rounded-lg">
                        @else
                            <div class="w-20 h-20 bg-gray-200 flex items-center justify-center rounded-lg text-gray-400">
                                No Image
                            </div>
                        @endif

                        <div>
                            <h2 class="font-semibold text-lg">
                                {{ $cart->product->name }}
                            </h2>

                            <p class="text-sm text-gray-500">
                                Rp {{ number_format($cart->product->dynamic_price) }}
                            </p>
                        </div>

                    </div>

                    <!-- ACTION -->
                    <div class="flex items-center gap-4">

                        <!-- UPDATE -->
                        <form action="/cart/{{ $cart->id }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PUT')

                            <input type="number"
                                   name="quantity"
                                   value="{{ $cart->quantity }}"
                                   min="1"
                                   class="w-16 border rounded px-2 py-1 text-center">

                            <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
                                Update
                            </button>
                        </form>

                        <!-- DELETE -->
                        <form action="/cart/{{ $cart->id }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button class="text-red-500 hover:underline text-sm">
                                Hapus
                            </button>
                        </form>

                    </div>

                </div>

                @empty

                <div class="p-6 text-center text-gray-500">
                    Keranjang masih kosong 🛒
                </div>

                @endforelse

            </div>

            <!-- TOTAL -->
            @if($carts->count() > 0)
            <div class="mt-6 bg-white p-6 rounded-xl shadow flex justify-between items-center">

                <div>
                    <p class="text-gray-500 text-sm">Total</p>
                    <h2 class="text-xl font-bold">
                        Rp {{ number_format($carts->sum(fn($c) => $c->product->dynamic_price * $c->quantity)) }}
                    </h2>
                </div>

                <button class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    Checkout
                </button>

            </div>
            @endif

        </div>

    </main>

</div>

</x-app-layout>
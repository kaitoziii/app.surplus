<!DOCTYPE html>
<html>
<head>
    <title>Detail Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<x-navbar />

@if(session('success'))
    <div class="max-w-6xl mx-auto mt-4">
        <div class="bg-green-100 text-green-700 p-3 rounded-lg shadow">
            {{ session('success') }}
        </div>
    </div>
@endif

<!-- CONTAINER -->
<div class="max-w-6xl mx-auto mt-6 bg-white p-6 rounded-lg shadow">

    <div class="grid grid-cols-2 gap-8">

        <!-- LEFT IMAGE -->
        <div class="relative">
            <img src="{{ $product->image_url }}" class="rounded-lg w-full">

            <!-- COUNTDOWN -->
            <div class="absolute bottom-0 left-0 bg-red-500 text-white px-4 py-2 rounded-tr-lg">
                Selamatkan Segera! ⏰ 
                <span id="countdown"></span>
            </div>
        </div>

        <!-- RIGHT DETAIL -->
        <div>

            <h1 class="text-2xl font-bold mb-2">
                {{ $product->name }}
            </h1>

            <div class="flex gap-2 mb-3">
                <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-sm">
                    {{ $product->stock }} tersedia
                </span>

                <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-sm">
                    Diskon
                </span>
            </div>
            
            <div class="flex justify-between items-center mb-2">

    <!-- Fav -->
    <img id="loveBtn"
     src="{{ asset('images/like icon.png') }}"
     class="w-6 cursor-pointer transition"
     onclick="toggleLove({{ $product->id }})">
</div>
            <!-- PRICE -->
            <div class="mb-4">
                <span class="line-through text-gray-400">
                    Rp {{ number_format($product->original_price,0,',','.') }}
                </span>

                <h2 class="text-3xl text-green-600 font-bold">
                    Rp {{ number_format($product->dynamic_price,0,',','.') }}
                </h2>
            </div>

            <!-- PICKUP -->
            <p class="text-gray-500 mb-4">
                Waktu pengambilan:
                {{ $product->pickup_deadline }}
            </p>

            <!-- FORM CART -->
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf

                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <!-- QUANTITY -->
                <div class="flex items-center gap-3 mb-4">
                    <span>Jumlah</span>

                    <button type="button" onclick="decrease()" class="px-3 py-1 border">-</button>

                    <input id="qty" name="quantity" type="number" value="1" min="1"
                           class="w-12 text-center border rounded">

                    <button type="button" onclick="increase()" class="px-3 py-1 border">+</button>
                </div>

                <!-- BUTTON -->
                <button class="bg-green-500 text-white px-6 py-3 rounded-lg w-full hover:bg-green-600">
                    + Keranjang
                </button>
            </form>

        </div>
    </div>

    <!-- STORE INFO -->
    <div class="mt-10 border-t pt-6 flex items-center gap-4">
        <img src="/logo.png" class="w-16 h-16 rounded">

        <div>
            <h3 class="font-bold">{{ $product->store->name }}</h3>
            <p class="text-gray-500 text-sm">
                {{ $product->store->category }}
            </p>
        </div>
    </div>

</div>

<!-- 🔥 COUNTDOWN SCRIPT -->
<script>
    const deadline = new Date("{{ $product->pickup_deadline }}").getTime();

    const countdown = setInterval(function () {
        const now = new Date().getTime();
        const distance = deadline - now;

        if (distance < 0) {
            clearInterval(countdown);
            document.getElementById("countdown").innerHTML = "Habis";
            return;
        }

        const hours = Math.floor((distance / (1000 * 60 * 60)));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

        document.getElementById("countdown").innerHTML =
            hours + "j " + minutes + "m";
    }, 1000);
</script>

<!-- 🔥 QUANTITY SCRIPT -->
<script>
function increase() {
    let qty = document.getElementById('qty');
    qty.value = parseInt(qty.value) + 1;
}

function decrease() {
    let qty = document.getElementById('qty');
    if (parseInt(qty.value) > 1) {
        qty.value = parseInt(qty.value) - 1;
    }
}
</script>

<script>
function toggleLove(productId) {
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];

    const index = favorites.indexOf(productId);

    if (index > -1) {
        favorites.splice(index, 1);
    } else {
        favorites.push(productId);
    }

    localStorage.setItem('favorites', JSON.stringify(favorites));
    updateLoveIcon(productId);
}

function updateLoveIcon(productId) {
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    let loveBtn = document.getElementById('loveBtn');

    if (favorites.includes(productId)) {
        loveBtn.src = "/images/like red icon.png"; // ❤️ merah
    } else {
        loveBtn.src = "/images/like icon.png"; // 🤍 hitam
    }
}

// auto load saat halaman dibuka
document.addEventListener("DOMContentLoaded", function () {
    updateLoveIcon({{ $product->id }});
});
</script>
</body>
</html>
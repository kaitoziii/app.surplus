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

@if(session('error'))
<div class="max-w-6xl mx-auto mt-4">
    <div class="bg-red-100 text-red-700 p-3 rounded-lg shadow">
        {{ session('error') }}
    </div>
</div>
@endif

<!-- CONTAINER -->
<div class="max-w-6xl mx-auto mt-6 bg-white p-6 rounded-lg shadow">

    <div class="grid grid-cols-2 gap-8">

        <!-- IMAGE -->
        <div class="relative">
            <img src="{{ $product->image_url }}" class="rounded-lg w-full">

            <div class="absolute bottom-0 left-0 bg-red-500 text-white px-4 py-2 rounded-tr-lg">
            Selamatkan produk sebelum <span id="countdown"></span>
            </div>
        </div>

        <!-- DETAIL -->
        <div>

            <h1 class="text-2xl font-bold mb-2">
                {{ $product->name }}
            </h1>

            <!-- STOCK -->
            <div class="flex gap-2 mb-3">
                <span class="px-2 py-1 rounded text-sm
                    {{ $product->available_stock <= 0 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                    {{ $product->available_stock }} tersedia
                </span>

                @if($product->discount_percentage > 0)
                <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-sm">
                    Diskon {{ $product->discount_percentage }}%
                </span>
                @endif
            </div>

            <!-- FAVORITE -->
            <div class="flex justify-between items-center mb-2">
                <img id="loveBtn"
                     src="{{ asset('images/like icon.png') }}"
                     class="w-6 cursor-pointer transition"
                     onclick="toggleLove({{ $product->id }})">
            </div>

            <!-- PRICE -->
            <div class="mb-4 flex items-center gap-2">
                <span class="line-through text-gray-400">
                    Rp {{ number_format($product->original_price,0,',','.') }}
                </span>
                <span class="text-green-500 font-bold text-xl">
                    Rp {{ number_format($product->dynamic_price,0,',','.') }}
                </span>
            </div>

            <!-- PICKUP -->
            <p class="text-gray-500 mb-2">
                Waktu pengambilan: {{ $product->pickup_deadline->format('d M Y H:i') }}
            </p>

            <!-- STATUS -->
            @php
                $stockAvailable = $product->available_stock > 0;
                $deadlineISO = $product->pickup_deadline ? $product->pickup_deadline->format('c') : null;
            @endphp

            <span id="productStatus" class="px-2 py-1 rounded text-sm
                {{ $stockAvailable ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                {{ $stockAvailable ? 'Ready to Grab' : 'Stok Habis' }}
            </span>

            <!-- FORM -->
            <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <!-- QTY -->
                <div class="flex items-center gap-3 mb-4">
                    <span>Jumlah</span>

                    <button type="button" onclick="decrease()" class="px-3 py-1 border">-</button>

                    <input id="qty" name="quantity" type="number" value="1" min="1"
                        class="w-12 text-center border rounded">

                    <button type="button" onclick="increase()" class="px-3 py-1 border">+</button>
                </div>

                <!-- BUTTON -->
                <button type="button"
                    onclick="handleAddToCart({{ $product->time_remaining_minutes }}, '{{ $product->pickup_deadline }}')"
                    @if($product->available_stock <= 0) disabled @endif
                    class="w-full py-3 rounded-lg font-bold text-white
                    {{ $product->available_stock <= 0 ? 'bg-gray-400' : 'bg-[#62865a] hover:bg-[#7fad74]' }}">
                    
                    {{ $product->available_stock <= 0 ? 'Stok Habis' : '+ Keranjang' }}
                </button>
            </form>

        </div>
    </div>

    <!-- STORE -->
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

<!-- MODAL -->
<div id="expiredModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-md p-6 rounded-xl shadow text-center">

        <h2 class="text-xl font-bold text-red-500 mb-2">
            Produk Sudah Expired
        </h2>

        <p id="expiredText" class="text-gray-600 mb-6"></p>

        <div class="flex gap-3">
            <button onclick="closeModal()" 
                class="w-1/2 border py-2 rounded-lg hover:bg-gray-100">
                Tidak
            </button>

            <button onclick="confirmExpired()" 
                class="w-1/2 bg-[#62865a] hover:bg-[#7fad74] text-white py-2 rounded-lg">
                Ya
            </button>
        </div>

    </div>
</div>

<!-- SCRIPT -->
<script>
// ADD TO CART
function handleAddToCart(timeRemaining, deadline) {
    if (timeRemaining <= 0) {
        document.getElementById("expiredText").innerHTML =
            "Produk ini sudah expired pada:<br><b>" + deadline + "</b><br><br>Apakah kamu tetap ingin membeli?";

        document.getElementById('expiredModal').classList.remove('hidden');
        document.getElementById('expiredModal').classList.add('flex');
    } else {
        document.getElementById('addToCartForm').submit();
    }
}

function confirmExpired() {
    document.getElementById('addToCartForm').submit();
}

function closeModal() {
    document.getElementById('expiredModal').classList.add('hidden');
}

// QTY
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

// COUNTDOWN
const countdownElem = document.getElementById("countdown");
const statusBadge = document.getElementById("productStatus");
const stockAvailable = {{ $product->available_stock > 0 ? 'true' : 'false' }};
const deadlineStr = "{{ $deadlineISO }}";

if (deadlineStr) {
    const deadline = new Date(deadlineStr).getTime();

    const countdownInterval = setInterval(() => {

    // 🔥 TAMBAHAN: STOP TIMER JIKA STOCK HABIS
    if (!stockAvailable) {
        countdownElem.innerText = "Stok Habis";

        statusBadge.innerText = "Stok Habis";
        statusBadge.classList.remove("bg-green-100","text-green-600");
        statusBadge.classList.add("bg-red-100","text-red-600");

        clearInterval(countdownInterval);
        return;
    }

    const now = new Date().getTime();
    const distance = deadline - now;

    if (distance <= 0) {
        countdownElem.innerText = "Expired";

        statusBadge.innerText = "Expired";
        statusBadge.classList.remove("bg-green-100","text-green-600");
        statusBadge.classList.add("bg-red-100","text-red-600");

        clearInterval(countdownInterval);
        return;
    }

    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    countdownElem.innerText = `${hours}j ${minutes}m ${seconds}s`;

}, 1000);
}

// FAVORITE
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
        loveBtn.src = "/images/like red icon.png";
    } else {
        loveBtn.src = "/images/like icon.png";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    updateLoveIcon({{ $product->id }});
});
</script>

</body>
</html>
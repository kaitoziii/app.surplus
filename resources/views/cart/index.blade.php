<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<x-navbar />

<div class="max-w-6xl mx-auto mt-6">

    <h1 class="text-2xl font-bold mb-6">Keranjang Saya</h1>

    @php $total = 0; @endphp

    <div class="grid grid-cols-3 gap-6">

        <!-- LIST PRODUK -->
        <div class="col-span-2 space-y-4">

            @forelse($cart as $item)

                @php
                    $subtotal = $item->product->dynamic_price * $item->quantity;
                    $total += $subtotal;
                @endphp

                <div class="bg-white p-4 rounded-lg shadow flex gap-4 items-center">

                    <!-- IMAGE -->
                    <img src="{{ $item->product->image_url }}" 
                         class="w-24 h-24 object-cover rounded">

                    <!-- INFO -->
                    <div class="flex-1">
                        <h2 class="font-bold">{{ $item->product->name }}</h2>
                        <p class="text-gray-500">
                            Rp {{ number_format($item->product->dynamic_price,0,',','.') }}
                        </p>

                        <!-- QUANTITY -->
                        <form id="form-{{ $item->id }}" action="{{ route('cart.update', $item->id) }}" method="POST">
                            @csrf

                            <div class="flex items-center gap-2 mt-2">

                                <!-- MINUS -->
                                <button type="button" 
                                    onclick="decrease({{ $item->id }}, {{ $item->product->dynamic_price }})"
                                    class="px-2 border">-</button>

                                <!-- INPUT -->
                                <input id="qty-{{ $item->id }}" 
                                       name="quantity"
                                       value="{{ $item->quantity }}"
                                       class="w-10 text-center border"
                                       onchange="updateSubtotal({{ $item->id }}, {{ $item->product->dynamic_price }})">

                                <!-- PLUS -->
                                <button type="button" 
                                    onclick="increase({{ $item->id }}, {{ $item->product->dynamic_price }})"
                                    class="px-2 border">+</button>

                                
                            </div>
                        </form>

                        <!-- SUBTOTAL -->
                        <p id="subtotal-{{ $item->id }}" class="mt-2 text-green-600 font-bold">
                            Subtotal: Rp {{ number_format($subtotal,0,',','.') }}
                        </p>
                    </div>

                    <!-- DELETE -->
                    <form action="{{ route('cart.delete', $item->id) }}" method="POST">
                        @csrf
                        <button class="text-red-500 text-sm hover:underline">
                            Hapus
                        </button>
                    </form>

                </div>

            @empty
                <p>Oops, keranjang kamu masih kosong</p>
                <p>Beli sekarang dan selamatkan produk favoritmu!</p>
            @endforelse

        </div>

        <!-- TOTAL -->
        <div class="bg-white p-6 rounded-lg shadow h-fit">

            <h2 class="text-lg font-bold mb-4">Ringkasan</h2>

            <div class="flex justify-between mb-2">
                <span>Total</span>
                <span id="total" class="font-bold">
                    Rp {{ number_format($total,0,',','.') }}
                </span>
            </div>

            <button class="bg-green-500 text-white w-full py-3 rounded-lg mt-4 hover:bg-green-600">
                Checkout
            </button>

        </div>

    </div>

</div>

<script>
function increase(id, price) {
    let qty = document.getElementById('qty-' + id);
    qty.value = parseInt(qty.value) + 1;

    updateSubtotal(id, price);
    submitForm(id); // 🔥 auto save
}

function decrease(id, price) {
    let qty = document.getElementById('qty-' + id);

    if (parseInt(qty.value) > 1) {
        qty.value = parseInt(qty.value) - 1;

        updateSubtotal(id, price);
        submitForm(id); // 🔥 auto save
    }
}

function updateSubtotal(id, price) {
    let qty = document.getElementById('qty-' + id).value;
    let subtotal = qty * price;

    document.getElementById('subtotal-' + id).innerHTML =
        "Subtotal: Rp " + subtotal.toLocaleString('id-ID');

    updateTotal();
}

function updateTotal() {
    let totals = document.querySelectorAll('[id^="subtotal-"]');
    let grandTotal = 0;

    totals.forEach(el => {
        let text = el.innerText.replace(/[^\d]/g, '');
        grandTotal += parseInt(text);
    });

    document.getElementById('total').innerText =
        "Rp " + grandTotal.toLocaleString('id-ID');
}

// 🔥 AUTO SUBMIT KE LARAVEL
function submitForm(id) {
    document.getElementById('form-' + id).submit();
}
</script>

</body>
</html>
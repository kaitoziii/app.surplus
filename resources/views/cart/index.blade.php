<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<x-navbar />

<div class="max-w-6xl mx-auto mt-6">

    <h1 class="text-3xl font-bold mb-4">Keranjang Saya</h1>

    @if(isset($merchant))
        <p class="text-lg md:text-xl text-black-700 mb-6">
            <span class="font-semibold text-xl md:text-2xl">{{ $merchant->name }}</span>
        </p>
    @endif

    @php 
        $total = 0;
        $hasOutOfStock = $cart->contains(fn($item) => $item->product->available_stock <= 0);
    @endphp

    <div class="grid grid-cols-3 gap-6">

        <!-- LIST PRODUK -->
        <div class="col-span-2 space-y-4">

            @forelse($cart as $item)
                @php
                    $subtotal = $item->product->dynamic_price * $item->quantity;
                    $total += $subtotal;
                    $availableStock = $item->product->available_stock;
                @endphp

                <div class="bg-white p-4 rounded-lg shadow flex gap-4 items-center">

                    <!-- PILIH PRODUK UNTUK CHECKOUT -->
                    <input type="checkbox" 
                           class="checkout-checkbox" 
                           data-merchant="{{ $item->product->store_id }}"
                           value="{{ $item->id }}">

                    <!-- IMAGE -->
                    <img src="{{ $item->product->image_url }}" 
                         class="w-24 h-24 object-cover rounded">

                    <!-- INFO -->
                    <div class="flex-1">

                        <h2 class="font-bold">
                            {{ $item->product->name }}
                            <span class="px-2 py-1 rounded-lg text-sm
                                {{ $item->product->pickup_deadline < now() ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                {{ $item->product->pickup_deadline < now() ? 'Expired' : 'Ready to Grab' }}
                            </span>
                        </h2>

                        <p class="text-gray-500">
                            Rp {{ number_format($item->product->dynamic_price,0,',','.') }}
                        </p>

                        <p class="text-sm
                            {{ $availableStock <= 0 ? 'text-red-500' : 'text-green-600' }}">
                            {{ $availableStock }} tersedia
                        </p>

                        <!-- QUANTITY -->
                        <div class="flex items-center gap-2 mt-2">
                            <button type="button" 
                                onclick="decrease({{ $item->id }}, {{ $item->product->dynamic_price }})"
                                class="px-2 border"
                                @if($availableStock <= 0) disabled @endif>-</button>
                            <input id="qty-{{ $item->id }}" 
                                   name="quantity"
                                   value="{{ $item->quantity }}"
                                   class="w-10 text-center border"
                                   onchange="updateSubtotal({{ $item->id }}, {{ $item->product->dynamic_price }})"
                                   @if($availableStock <= 0) disabled @endif>
                            <button type="button" 
                                onclick="increase({{ $item->id }}, {{ $item->product->dynamic_price }})"
                                class="px-2 border"
                                @if($availableStock <= 0) disabled @endif>+</button>
                        </div>

                        <p id="subtotal-{{ $item->id }}" class="mt-2 text-green-600 font-bold">
                            Subtotal: Rp {{ number_format($subtotal,0,',','.') }}
                        </p>

                    </div>

                    <!-- DELETE -->
                    <form action="{{ route('cart.delete', $item->id) }}" method="POST">
                        @csrf
                        <button class="text-red-500 text-sm hover:underline">Hapus</button>
                    </form>

                </div>

            @empty
                <p>Oops, keranjang kamu masih kosong</p>
                <p class="text-gray-500">Beli sekarang dan selamatkan produk favoritmu!</p>
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

            <!-- CHECKOUT BUTTON -->
            <form action="{{ route('checkout.index') }}" method="GET" id="checkoutForm">
                @csrf
                <input type="hidden" name="product_ids" id="product_ids">

            <button type="button"
                id="checkoutBtn"
                @if($hasOutOfStock) disabled @endif
                class="w-full bg-[#62865a] hover:bg-[#7fad74] disabled:bg-gray-400 text-white py-4 rounded-lg font-bold text-lg">
                {{ $hasOutOfStock ? 'Stok tidak tersedia' : 'Lanjut' }}
            </button>
        </form>
        </div>

    </div>

</div>

<!-- Modal -->
<div id="checkoutModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full text-center">
        <h3 class="text-lg font-bold mb-4 text-red-600">Oops!</h3>
        <p class="mb-6">Pilih minimal 1 produk untuk Checkout!</p>
        <button id="closeModal" class="bg-[#62865a] hover:bg-[#7fad74] text-white px-4 py-2 rounded-lg font-semibold">
            Tutup
        </button>
    </div>
</div>

<script>
// QUANTITY UPDATE CLIENT-SIDE (tidak mengurangi stok server)
function increase(id, price) {
    let qty = document.getElementById('qty-' + id);
    if(qty.value < 99) qty.value = parseInt(qty.value) + 1;
    updateSubtotal(id, price);
}

function decrease(id, price) {
    let qty = document.getElementById('qty-' + id);
    if(parseInt(qty.value) > 1) {
        qty.value = parseInt(qty.value) - 1;
        updateSubtotal(id, price);
    }
}

function updateSubtotal(id, price) {
    let qty = document.getElementById('qty-' + id).value;
    let subtotal = qty * price;
    document.getElementById('subtotal-' + id).innerText =
        "Subtotal: Rp " + subtotal.toLocaleString('id-ID');
    updateTotal();
}

function updateTotal() {
    const checkedBoxes = document.querySelectorAll('.checkout-checkbox:checked');
    let grandTotal = 0;

    checkedBoxes.forEach(cb => {
        const id = cb.value;
        const subtotalText = document.getElementById('subtotal-' + id).innerText;
        const subtotalNumber = parseInt(subtotalText.replace(/[^\d]/g, ''));
        grandTotal += subtotalNumber;
    });

    document.getElementById('total').innerText =
        "Rp " + grandTotal.toLocaleString('id-ID');
}

// PILIH PRODUK UNTUK CHECKOUT (1 MERCHANT)
const checkboxes = document.querySelectorAll('.checkout-checkbox');
let selectedMerchant = null;

checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const merchantId = this.dataset.merchant;
        if (this.checked) {
            if (!selectedMerchant) selectedMerchant = merchantId;
            else if (selectedMerchant !== merchantId) {
                alert("Kamu hanya bisa Checkout produk dari 1 merchant dalam 1 pesanan!");
                this.checked = false;
            }
        } else {
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            if (!anyChecked) selectedMerchant = null;
        }

        updateTotal();
    });
});

// SUBMIT CHECKOUT
document.getElementById('checkoutBtn').addEventListener('click', function () {
    const checkedIds = Array.from(document.querySelectorAll('.checkout-checkbox'))
        .filter(cb => cb.checked)
        .map(cb => cb.value);

    // ❌ kalau belum pilih produk → STOP
    if (checkedIds.length === 0) {
        document.getElementById('checkoutModal').classList.remove('hidden');
        return;
    }

    // isi hidden input
    document.getElementById('product_ids').value = checkedIds.join(',');

    // baru submit form
    document.getElementById('checkoutForm').submit();
});

// TUTUP MODAL
document.getElementById('closeModal').addEventListener('click', function() {
    document.getElementById('checkoutModal').classList.add('hidden');
});
</script>

</body>
</html>
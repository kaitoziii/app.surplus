<!DOCTYPE html>
<html>
<head>
    <title>Atur Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<x-navbar />

<form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="max-w-5xl mx-auto mt-6 bg-white p-8 rounded-xl shadow space-y-6">

    <h1 class="text-2xl font-bold">Atur Pesanan</h1>

    <!-- 1. Alamat -->
    <div class="flex items-center gap-2 mb-2">
    <img src="{{ asset('images/location icon.png') }}" 
         alt="icon lokasi" 
         class="w-5 h-5">

    <h2 class="font-semibold text-lg">
        Alamat Pengambilan
    </h2>
    </div>

    <p class="text-gray-600">
    {{ $store->address ?? 'Alamat belum diisi merchant' }}
    </p>


    <!-- 2. Jam -->
<div>

    <div class="flex items-center gap-2 mb-2">
        <img src="{{ asset('images/time icon.png') }}" 
             alt="icon jam" 
             class="w-5 h-5">

        <h2 class="font-semibold text-lg">
            Jam Pengambilan
        </h2>
    </div>

    <p class="text-gray-600">
        {{ optional($store->pickup_start)->format('d M Y H:i') ?? '-' }}
        -
        {{ optional($store->pickup_end)->format('d M Y H:i') ?? '-' }}
    </p>

</div>

    <!-- 3. Metode Pengambilan -->
    <div>
        <h2 class="font-semibold mb-3 text-lg">Metode Pengambilan</h2>

        <div class="grid grid-cols-2 gap-4">

            <label class="cursor-pointer">
                <input type="radio" name="pickup_method" value="take_away" class="hidden peer" checked>

                <div class="border rounded-lg p-4 text-center peer-checked:border-green-500 peer-checked:bg-green-50">
                    <p class="font-semibold">Take Away</p>
                    <p class="text-sm text-gray-500">Ambil sendiri</p>
                </div>
            </label>

            <label class="cursor-pointer">
                <input type="radio" name="pickup_method" value="delivery" class="hidden peer">

                <div class="border rounded-lg p-4 text-center peer-checked:border-green-500 peer-checked:bg-green-50">
                    <p class="font-semibold">Delivery</p>
                    <p class="text-sm text-gray-500">Diantar ke alamat</p>
                </div>
            </label>

        </div>
    </div>

    <!-- 4. Pesanan -->
<div>

    <div class="flex items-center gap-2 mb-3">
        <img src="{{ asset('images/product icon.png') }}" 
             alt="icon pesanan" 
             class="w-5 h-5">

        <h2 class="font-semibold text-lg">
            Pesanan
        </h2>
    </div>

    @foreach($cartItems as $item)
    <div class="flex items-center gap-4 border-b pb-4 mb-4">

        <img src="{{ $item->product->image_url }}" class="w-20 h-20 rounded object-cover">

        <div class="flex-1">
            <p class="font-bold">
                {{ $item->product->name }}

                <span class="px-2 py-1 rounded text-sm
                    {{ $item->product->pickup_deadline < now() ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                    {{ $item->product->pickup_deadline < now() ? 'Expired' : 'Ready to Grab' }}
                </span>
            </p>

            <p class="text-sm text-gray-500">
                Jumlah: {{ $item->quantity }}
            </p>
        </div>

        <div class="text-right">
            <p class="line-through text-gray-400 text-sm">
                Rp {{ number_format($item->product->original_price,0,',','.') }}
            </p>
            <p class="text-green-600 font-bold text-lg">
                Rp {{ number_format($item->product->dynamic_price,0,',','.') }}
            </p>
        </div>

    </div>
    @endforeach
</div>

    <!-- 5. Pembayaran -->
    <div>
        <h2 class="font-semibold mb-3 text-lg">Rincian Pembayaran</h2>

        <div class="space-y-2 text-gray-700">

            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>Rp {{ number_format($subtotal,0,',','.') }}</span>
            </div>

            <div class="flex justify-between">
                <span>Biaya Penanganan</span>
                <span>Rp {{ number_format($handlingFee,0,',','.') }}</span>
            </div>

            <div class="flex justify-between font-bold text-green-600 text-lg">
                <span>Total</span>
                <span>Rp {{ number_format($totalPayment,0,',','.') }}</span>
            </div>

        </div>
    </div>

    <!-- 6. Metode Pembayaran -->
    <div>
        <h2 class="font-semibold mb-3 text-lg">Metode Pembayaran</h2>

        <div class="grid grid-cols-2 gap-4">

            <label class="cursor-pointer">
                <input type="radio" name="payment_method" value="cash" class="hidden peer" checked>

                <div class="border rounded-lg p-4 text-center peer-checked:border-green-500 peer-checked:bg-green-50">
                    Cash
                </div>
            </label>

            <label class="cursor-pointer">
                <input type="radio" name="payment_method" value="transfer" class="hidden peer">

                <div class="border rounded-lg p-4 text-center peer-checked:border-green-500 peer-checked:bg-green-50">
                    Transfer
                </div>
            </label>

        </div>

        <!-- Upload bukti transfer -->
        <div id="transferUpload" class="mt-4 hidden">
            <label class="block mb-2">Upload Bukti Transfer</label>

            <input type="file" name="transfer_proof" id="transferProof"
                   class="border rounded-lg w-full p-2" accept="image/*">

            <p id="transferError" class="text-red-600 text-sm mt-1 hidden">
                Harap upload bukti transfer!
            </p>
        </div>
    </div>

    <!-- BUTTON -->
    <button type="submit"
        class="w-full bg-[#62865a] hover:bg-[#7fad74] text-white py-4 rounded-lg font-bold text-lg">
        Buat Pesanan
    </button>

</div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const uploadDiv = document.getElementById('transferUpload');
    const transferProof = document.getElementById('transferProof');
    const transferError = document.getElementById('transferError');
    const form = document.querySelector('form');

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'transfer') {
                uploadDiv.classList.remove('hidden');
            } else {
                uploadDiv.classList.add('hidden');
                transferError.classList.add('hidden');
            }
        });
    });

    form.addEventListener('submit', function (event) {

        const selected = document.querySelector('input[name="payment_method"]:checked')?.value;

        if (selected === 'transfer' && !transferProof.value) {
            event.preventDefault();
            transferError.classList.remove('hidden');
        }

    });

});
</script>

</body>
</html>
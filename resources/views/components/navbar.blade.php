<div class="bg-[#7A9B62] p-6 rounded-b-3xl text-white">

    <div class="flex justify-between items-center mb-4">
        <div>
            <p class="text-sm">Lokasi saat ini</p>
            <h2 class="font-bold">Semarang ⌄</h2>
        </div>

        <div class="flex gap-4 items-center">
            <a href="/favorites">
                <img src="{{ asset('images/like icon.png') }}" class="w-6 invert">
            </a>

            <a href="/cart">
                <img src="{{ asset('images/cart icon.png') }}" class="w-6 invert">
            </a>
        </div>
    </div>

    <input 
        type="text" 
        placeholder="Mau selamatkan produk apa hari ini?"
        class="w-full p-3 rounded-lg text-black"
    >
</div>
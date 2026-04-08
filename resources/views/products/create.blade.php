<x-app-layout>

<div class="max-w-xl mx-auto mt-6 bg-white p-6 rounded-xl shadow">

    <h1 class="text-xl font-bold mb-4">Tambah Produk</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Nama -->
        <div class="mb-3">
            <input type="text" name="name" placeholder="Nama Produk"
                   class="w-full border rounded px-3 py-2" required>
        </div>

        <!-- Deskripsi -->
        <div class="mb-3">
            <textarea name="description" placeholder="Deskripsi"
                      class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <!-- Harga -->
        <div class="mb-3">
            <input type="number" name="original_price" placeholder="Harga"
                   class="w-full border rounded px-3 py-2" required>
        </div>

        <!-- Stock -->
        <div class="mb-3">
            <input type="number" name="stock" placeholder="Stock"
                   class="w-full border rounded px-3 py-2" required>
        </div>

        <!-- Unit -->
        <div class="mb-3">
            <input type="text" name="unit" placeholder="Unit (pcs, box, dll)"
                   class="w-full border rounded px-3 py-2">
        </div>

        <!-- Category -->
        <div class="mb-3">
            <input type="text" name="category" placeholder="Kategori"
                   class="w-full border rounded px-3 py-2">
        </div>

        <!-- Expiry -->
        <div class="mb-3">
            <label class="text-sm text-gray-500">Tanggal Kadaluarsa</label>
            <input type="date" name="expiry_date"
                   class="w-full border rounded px-3 py-2">
        </div>

        <!-- Pickup Deadline -->
        <div class="mb-3">
            <label class="text-sm text-gray-500">Batas Ambil</label>
            <input type="datetime-local" name="pickup_deadline"
                   class="w-full border rounded px-3 py-2">
        </div>

        <!-- Gambar -->
        <div class="mb-3">
            <input type="file" name="image"
                   class="w-full border rounded px-3 py-2">
        </div>

        <button type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg w-full">
            Simpan
        </button>
    </form>

</div>

</x-app-layout>
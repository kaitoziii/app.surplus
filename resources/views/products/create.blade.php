<x-app-layout>
    
    <!-- CONTENT -->
    <main class="flex-1 bg-gray-100 min-h-screen p-6">

        <div class="max-w-3xl mx-auto bg-white p-6 rounded-2xl shadow">

            <!-- HEADER -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold">Tambah Produk</h1>
                <p class="text-sm text-gray-500">
                    Tambahkan produk surplus untuk dijual
                </p>
            </div>

            <!-- FORM -->
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Nama -->
                <div>
                    <label class="text-sm text-gray-600">Nama Produk</label>
                    <input type="text" name="name"
                        class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200" required>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="text-sm text-gray-600">Deskripsi</label>
                    <textarea name="description"
                        class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200"></textarea>
                </div>

                <!-- Harga & Stock -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Harga</label>
                        <input type="number" name="original_price"
                            class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200" required>
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Stock</label>
                        <input type="number" name="stock"
                            class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200" required>
                    </div>
                </div>

                <!-- Unit & Category -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Unit</label>
                        <input type="text" name="unit"
                            class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Kategori</label>
                        <input type="text" name="category"
                            class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200">
                    </div>
                </div>

                <!-- Expiry & Pickup -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Tanggal Kadaluarsa</label>
                        <input type="date" name="expiry_date"
                            class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Batas Ambil</label>
                        <input type="datetime-local" name="pickup_deadline"
                            class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200">
                    </div>
                </div>

                <!-- Gambar -->
                <div>
                    <label class="text-sm text-gray-600">Upload Gambar</label>
                    <input type="file" name="image"
                        class="w-full border rounded-lg px-3 py-2 mt-1">
                </div>

                <!-- BUTTON -->
                <div class="flex justify-end gap-3 pt-4">

                    <a href="{{ route('products.index') }}"
                       class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100">
                        Batal
                    </a>

                    <button type="submit"
                        class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                        Simpan Produk
                    </button>

                </div>

            </form>

        </div>

    </main>

</div>

</x-app-layout>
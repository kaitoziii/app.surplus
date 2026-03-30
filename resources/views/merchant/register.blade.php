<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Merchant</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans">

<!-- NAVBAR -->
<nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">Surplus</h1>

    <div class="space-x-6 hidden md:flex">
        <a href="#" class="text-gray-700">Beranda</a>
        <a href="#" class="text-gray-700">Produk</a>
        <a href="#" class="text-gray-700">Program</a>
        <a href="#" class="text-gray-700">Tentang Kami</a>
    </div>

    <button class="border border-teal-500 text-teal-500 px-4 py-1 rounded">
        Gabung Mitra
    </button>
</nav>

<!-- HERO -->
<section class="bg-teal-500 text-white px-6 md:px-16 py-12 flex flex-col md:flex-row items-center justify-between">
    <div>
        <h2 class="text-3xl font-bold mb-3">Daftarkan Bisnis mu Sekarang!</h2>
        <p class="mb-2">Ubah Produk Overstock Menjadi Keuntungan</p>
        <p class="text-sm mb-4">Jual Produk Overstock anda dengan Surplus!</p>

        <button class="bg-white text-teal-600 px-4 py-2 rounded shadow">
            Daftar Sekarang!
        </button>
    </div>

    <img src="https://cdn-icons-png.flaticon.com/512/3075/3075977.png"
         class="w-40 mt-6 md:mt-0">
</section>

<!-- FORM -->
<section class="max-w-5xl mx-auto bg-white p-8 mt-8 rounded shadow">
    <form method="POST" action="#">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <input type="text" placeholder="Nama Badan Usaha" class="input">
            <input type="text" placeholder="Nama Bisnis" class="input">

            <input type="text" placeholder="Kategori Produk" class="input">
            <input type="email" placeholder="Email Bisnis" class="input">

            <input type="text" placeholder="Nama Pemilik Bisnis" class="input">
            <input type="text" placeholder="Akun Instagram Bisnis" class="input">

            <input type="text" placeholder="Nomor Telepon Pemilik Bisnis" class="input">
            <input type="text" placeholder="Penjualan per hari" class="input">

            <input type="text" placeholder="Alamat Bisnis" class="input md:col-span-2">
            <input type="text" placeholder="Kota" class="input md:col-span-2">

        </div>

        <button class="mt-6 bg-teal-500 text-white px-6 py-2 rounded hover:bg-teal-600">
            Submit Form
        </button>
    </form>
</section>

</body>
</html>
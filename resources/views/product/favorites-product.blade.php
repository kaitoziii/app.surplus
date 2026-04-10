<!DOCTYPE html>
<html>
<head>
    <title>Favorites</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<x-navbar />

<div class="max-w-5xl mx-auto mt-6">

    <h1 class="text-2xl font-bold mb-4">Produk Favorit</h1>

    <div id="favoritesList" class="grid grid-cols-3 gap-4"></div>

</div>

<script>
const products = @json(\App\Models\Product::all());

function loadFavorites() {
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    let container = document.getElementById('favoritesList');

    container.innerHTML = "";

    if (favorites.length === 0) {
        container.innerHTML = "<p>Belum ada merchant yang disukai</p>";
        return;
    }

    products.forEach(product => {
        if (favorites.includes(product.id)) {
            container.innerHTML += `
                <div class="bg-white p-4 rounded shadow">
                    <img src="${product.image_url}" class="w-full h-40 object-cover rounded">
                    <h3 class="font-bold mt-2">${product.name}</h3>
                    <p>Rp ${product.dynamic_price}</p>
                    <a href="/product/${product.id}" class="text-blue-500">Lihat</a>
                </div>
            `;
        }
    });
}

document.addEventListener("DOMContentLoaded", loadFavorites);
</script>

</body>
</html>
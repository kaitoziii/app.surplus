<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
</head>
<body>

<h1>🛒 Cart Page</h1>

<!-- FORM TAMBAH KE CART -->
<form action="/cart/add" method="POST">
    @csrf

    <label>Product ID:</label><br>
    <input type="number" name="product_id" value="1"><br><br>

    <label>Quantity:</label><br>
    <input type="number" name="quantity" value="1" min="1"><br><br>

    <button type="submit">+ Tambah ke Keranjang</button>
</form>

<hr>

<!-- LINK LIHAT DATA CART -->
<a href="/cart">👉 Lihat Isi Cart</a>

</body>
</html>
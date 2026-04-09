<h1>Detail Produk</h1>

<p>Nama Produk: Nasi Goreng</p>
<p>Harga: 15000</p>

<form action="/cart/add" method="POST">
    @csrf

    <input type="hidden" name="product_id" value="1">

    <button type="button" onclick="kurang()">-</button>
    <input type="number" name="quantity" id="qty" value="1" min="1">
    <button type="button" onclick="tambah()">+</button>

    <br><br>

    <button type="submit">+ Keranjang</button>
</form>

<script>
function tambah() {
    let qty = document.getElementById('qty');
    qty.value = parseInt(qty.value) + 1;
}

function kurang() {
    let qty = document.getElementById('qty');
    if (qty.value > 1) {
        qty.value = parseInt(qty.value) - 1;
    }
}
</script>
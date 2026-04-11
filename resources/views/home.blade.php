<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>

<h1>HOME PAGE</h1>

<form method="GET">
    <input type="text" name="search" placeholder="Search store..." value="{{ $search }}">
    <button type="submit">Search</button>
</form>

<hr>

<h2>Stores</h2>
@foreach($stores as $store)
    <div>
        <h3>{{ $store->name }}</h3>
        <p>Active Products: {{ $store->active_products_count }}</p>
    </div>
@endforeach

<hr>

<h2>Urgent Products</h2>
@foreach($urgentProducts as $product)
    <div>
        <p>{{ $product->name ?? 'No Name' }}</p>
    </div>
@endforeach

<hr>

<h2>Latest Products</h2>
@foreach($latestProducts as $product)
    <div>
        <p>{{ $product->name ?? 'No Name' }}</p>
    </div>
@endforeach

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $store->name }} — App.Surplus</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
:root {
  --pine:#202808; --kombu:#33432B; --dingley:#6A784D;
  --brandy:#DEC59E; --copper:#C4866D; --cream:#F5F0E8;
  --sage:#8FA67A; --moss:#4A5E35;
}
  * { box-sizing:border-box; }
  body { background:#F7F4EE; font-family:'Inter',system-ui,sans-serif; margin:0; }

  .navbar { background:var(--pine); position:sticky; top:0; z-index:50; box-shadow:0 2px 12px rgba(0,0,0,.18); }
  .navbar-inner { max-width:1100px; margin:0 auto; padding:0 20px; height:60px; display:flex; align-items:center; gap:16px; }
  .back-btn { display:flex; align-items:center; gap:7px; color:rgba(255,255,255,.8); text-decoration:none; font-size:14px; }
  .back-btn:hover { color:#fff; }
  .nav-title { color:#fff; font-size:15px; font-weight:600; flex:1; text-align:center; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .nav-cart { color:rgba(255,255,255,.8); text-decoration:none; display:flex; align-items:center; gap:5px; font-size:13px; }
  .nav-cart:hover { color:#fff; }

  .store-hero { background:linear-gradient(135deg, var(--pine) 0%, var(--dingley) 100%); padding:28px 20px; }
  .store-hero-inner { max-width:1100px; margin:0 auto; display:flex; align-items:center; gap:20px; }
  .store-hero-avatar { width:64px; height:64px; background:rgba(255,255,255,.2); border-radius:16px;
    display:flex; align-items:center; justify-content:center; font-size:26px; font-weight:700; color:#fff; flex-shrink:0; }
  .store-hero-info h1 { color:#fff; font-size:20px; font-weight:700; margin:0 0 4px; }
  .store-hero-info p { color:rgba(255,255,255,.7); font-size:13px; margin:0 0 10px; }
  .store-hero-stats { display:flex; gap:12px; flex-wrap:wrap; }
  .store-stat { background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.2); border-radius:10px; padding:8px 14px; }
  .store-stat .num { color:#fff; font-size:16px; font-weight:700; line-height:1; }
  .store-stat .lbl { color:rgba(255,255,255,.65); font-size:10px; margin-top:2px; }

  .main { max-width:1100px; margin:0 auto; padding:24px 20px 100px; }
  .section-title { font-size:16px; font-weight:700; color:var(--pine); margin-bottom:16px; display:flex; align-items:center; gap:8px; }
  .section-title::before { content:''; width:4px; height:18px; background:var(--brandy); border-radius:2px; display:block; }

  .product-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(240px,1fr)); gap:16px; }
  .product-card { background:#fff; border-radius:16px; border:1px solid #EDE8DF; overflow:hidden; text-decoration:none; display:block; transition:.2s; }
  .product-card:hover { transform:translateY(-3px); box-shadow:0 10px 28px rgba(45,80,22,.12); }
  .product-img { width:100%; height:160px; object-fit:cover; background:#f0ebe3; }
  .product-img-placeholder { width:100%; height:160px; background:linear-gradient(135deg,#f5ede3,#ede3d5);
    display:flex; align-items:center; justify-content:center; }
  .product-body { padding:14px; }
  .product-category { font-size:10px; font-weight:600; color:var(--dingley); text-transform:uppercase; letter-spacing:.05em; margin-bottom:4px; }
  .product-name { font-size:14px; font-weight:700; color:var(--pine); margin-bottom:6px; }
  .product-desc { font-size:12px; color:#888; line-height:1.4; margin-bottom:10px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .product-footer { display:flex; align-items:center; justify-content:space-between; }
  .product-price-wrap {}
  .product-price-disc { font-size:15px; font-weight:700; color:var(--copper); }
  .product-price-orig { font-size:11px; color:#bbb; text-decoration:line-through; }
  .product-stock { font-size:11px; color:#888; }
  .product-stock span { color:var(--dingley); font-weight:600; }
  .urgent-tag { display:inline-flex; align-items:center; gap:3px; background:#FFF0EB; color:#FF6B35; font-size:9px; font-weight:700; padding:2px 7px; border-radius:20px; margin-bottom:6px; }
  .deadline-tag { display:inline-flex; align-items:center; gap:3px; background:#F0F7EB; color:var(--dingley); font-size:9px; font-weight:600; padding:2px 7px; border-radius:20px; }

  .empty { text-align:center; padding:60px 20px; }
  .empty-icon { font-size:48px; margin-bottom:12px; }
  .empty h3 { font-size:17px; font-weight:700; color:var(--pine); margin:0 0 6px; }
  .empty p { font-size:14px; color:#888; margin:0 0 20px; }
  .empty a { background:var(--pine); color:#fff; padding:10px 24px; border-radius:22px; text-decoration:none; font-size:13px; font-weight:600; }

  .bottom-nav { display:none; position:fixed; bottom:0; left:0; right:0; background:var(--pine); border-top:1px solid var(--kombu); padding:6px 0 8px; z-index:50; }
  .bottom-nav-inner { display:grid; grid-template-columns:repeat(4,1fr); }
  .bottom-nav a { display:flex; flex-direction:column; align-items:center; gap:3px; text-decoration:none; padding:4px 0; }
  .bottom-nav a span { font-size:9px; color:rgba(255,255,255,.55); }
  .bottom-nav a.active span { color:var(--brandy); font-weight:600; }

  @media(max-width:768px) {
    .bottom-nav { display:block; }
    .product-grid { grid-template-columns:1fr 1fr; gap:10px; }
    .product-img, .product-img-placeholder { height:130px; }
  }
</style>
</head>
<body>

<nav class="navbar">
  <div class="navbar-inner">
    <a href="{{ route('home') }}" class="back-btn">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      Kembali
    </a>
    <span class="nav-title">{{ $store->name }}</span>
    <a href="{{ route('cart.index') }}" class="nav-cart">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    </a>
  </div>
</nav>

<section class="store-hero">
  <div class="store-hero-inner">
    <div class="store-hero-avatar">{{ strtoupper(substr($store->name, 0, 1)) }}</div>
    <div class="store-hero-info">
      <h1>{{ $store->name }}</h1>
      <p>📍 {{ $store->address }}</p>
      <div class="store-hero-stats">
        <div class="store-stat">
          <div class="num">{{ $products->count() }}</div>
          <div class="lbl">Produk Tersedia</div>
        </div>
        <div class="store-stat">
          <div class="num">Rp {{ number_format($products->min('dynamic_price') ?? $products->min('discounted_price') ?? 0, 0, ',', '.') }}</div>
          <div class="lbl">Harga Mulai</div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="main">
  <div class="section-title">Produk Surplus Tersedia</div>

  @if($products->count() > 0)
  <div class="product-grid">
    @foreach($products as $product)
    <a href="{{ route('product.detail', $product->id) }}" class="product-card">
      @if($product->image_url)
        <img src="{{ asset('storage/'.$product->image_url) }}" alt="{{ $product->name }}" class="product-img">
      @else
        <div class="product-img-placeholder">
          <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#C4956A"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
      @endif
      <div class="product-body">
        @if(isset($product->category) && $product->category)
          <div class="product-category">{{ $product->category->name ?? $product->category }}</div>
        @endif

        @php
          $deadline = \Carbon\Carbon::parse($product->pickup_deadline);
          $isUrgent = $deadline->diffInHours(now()) <= 3;
        @endphp

        @if($isUrgent)
          <div class="urgent-tag">⚡ Segera habis · {{ $deadline->diffForHumans() }}</div>
        @endif

        <div class="product-name">{{ $product->name }}</div>
        @if($product->description)
          <div class="product-desc">{{ $product->description }}</div>
        @endif

        <div class="product-footer">
          <div class="product-price-wrap">
            <div class="product-price-disc">Rp {{ number_format($product->dynamic_price ?? $product->discounted_price, 0, ',', '.') }}</div>
            @if(isset($product->original_price) && $product->original_price > ($product->dynamic_price ?? $product->discounted_price))
              <div class="product-price-orig">Rp {{ number_format($product->original_price, 0, ',', '.') }}</div>
            @endif
          </div>
          <div class="product-stock">Sisa <span>{{ $product->stock }}</span></div>
        </div>

        <div style="margin-top:8px">
          <div class="deadline-tag">🕐 Ambil sebelum {{ $deadline->format('H:i') }}</div>
        </div>
      </div>
    </a>
    @endforeach
  </div>
  @else
  <div class="empty">
    <div class="empty-icon">🍽️</div>
    <h3>Sedang tidak ada produk</h3>
    <p>Merchant ini belum memiliki surplus saat ini. Cek lagi nanti!</p>
    <a href="{{ route('home') }}">Lihat merchant lain</a>
  </div>
  @endif
</div>

<nav class="bottom-nav">
  <div class="bottom-nav-inner">
    <a href="{{ route('home') }}">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.6)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
      <span>Beranda</span>
    </a>
    <a href="{{ route('cart.index') }}" class="active">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="var(--brandy)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      <span style="color:var(--brandy)">Keranjang</span>
    </a>
    <a href="{{ route('my-orders.index') }}">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.6)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      <span>Pesanan</span>
    </a>
    <a href="{{ route('profile.edit') }}">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.6)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
      <span>Profil</span>
    </a>
  </div>
</nav>

</body>
</html>
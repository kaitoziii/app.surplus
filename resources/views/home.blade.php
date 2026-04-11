<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>App.Surplus — Selamatkan Makanan, Hemat Belanja</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  :root {
  --pine:#202808; --kombu:#33432B; --dingley:#6A784D;
  --brandy:#DEC59E; --copper:#C4866D; --cream:#F5F0E8;
  --sage:#8FA67A; --moss:#4A5E35;
}
  * { box-sizing:border-box; }
  body { background:#F7F4EE; font-family:'Inter',system-ui,sans-serif; margin:0; }

  /* NAVBAR */
  .navbar { background:var(--pine); position:sticky; top:0; z-index:50;
    box-shadow:0 2px 12px rgba(0,0,0,.18); }
  .navbar-inner { max-width:1100px; margin:0 auto; padding:0 20px;
    height:60px; display:flex; align-items:center; gap:16px; }
  .logo { display:flex; align-items:center; gap:9px; text-decoration:none; flex-shrink:0; }
  .logo-box { width:32px; height:32px; background:var(--brandy);
    border-radius:8px; display:flex; align-items:center; justify-content:center; }
  .logo-box span { color:#fff; font-size:14px; font-weight:700; }
  .logo-text { color:#fff; font-size:16px; font-weight:700; letter-spacing:-.3px; }
  .logo-text span { color:var(--brandy); }
  .search-wrap { flex:1; max-width:480px; position:relative; }
  .search-wrap input { width:100%; padding:9px 16px 9px 40px;
    border-radius:22px; border:none; background:rgba(255,255,255,.12);
    color:#fff; font-size:14px; outline:none; transition:.2s; }
  .search-wrap input::placeholder { color:rgba(255,255,255,.5); }
  .search-wrap input:focus { background:rgba(255,255,255,.2); }
  .search-wrap svg { position:absolute; left:13px; top:50%;
    transform:translateY(-50%); opacity:.6; }
  .nav-links { display:flex; align-items:center; gap:4px; margin-left:auto; }
  .nav-btn { display:flex; align-items:center; gap:6px; padding:7px 14px;
    border-radius:20px; text-decoration:none; font-size:13px;
    font-weight:500; color:rgba(255,255,255,.8); transition:.2s; position:relative; }
  .nav-btn:hover { background:rgba(255,255,255,.1); color:#fff; }
  .nav-btn.active { color:#fff; background:rgba(255,255,255,.15); }
  .cart-badge { position:absolute; top:2px; right:6px; background:var(--brandy);
    color:#fff; font-size:9px; font-weight:700; width:16px; height:16px;
    border-radius:50%; display:flex; align-items:center; justify-content:center; }

  /* HERO */
  .hero { background:linear-gradient(135deg, var(--pine) 0%, var(--moss) 60%, var(--dingley) 100%);
    padding:48px 20px 40px; position:relative; overflow:hidden; }
  .hero::before { content:''; position:absolute; inset:0;
    background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
  .hero-inner { max-width:1100px; margin:0 auto; position:relative; }
  .hero h1 { color:#fff; font-size:clamp(22px,4vw,34px); font-weight:700;
    margin:0 0 10px; line-height:1.25; }
  .hero h1 span { color:var(--brandy); }
  .hero p { color:rgba(255,255,255,.75); font-size:15px; margin:0 0 24px; max-width:520px; }
  .hero-stats { display:flex; gap:20px; flex-wrap:wrap; }
  .hero-stat { background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.15);
    border-radius:12px; padding:12px 18px; }
  .hero-stat .num { color:#fff; font-size:20px; font-weight:700; line-height:1; }
  .hero-stat .lbl { color:rgba(255,255,255,.65); font-size:11px; margin-top:3px; }

  /* MAIN */
  .main { max-width:1100px; margin:0 auto; padding:28px 20px 100px; }
  .section-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
  .section-title { font-size:17px; font-weight:700; color:var(--pine); display:flex; align-items:center; gap:8px; }
  .section-title::before { content:''; width:4px; height:20px;
    background:var(--brandy); border-radius:2px; display:block; }
  .see-all { font-size:13px; color:var(--dingley); text-decoration:none; font-weight:500; }
  .see-all:hover { color:var(--pine); }

  /* URGENT STRIP */
  .urgent-scroll { display:flex; gap:12px; overflow-x:auto; padding-bottom:6px; scrollbar-width:none; }
  .urgent-scroll::-webkit-scrollbar { display:none; }
  .urgent-card { flex-shrink:0; width:160px; background:#fff;
    border-radius:14px; border:1.5px solid #FFE0CC; overflow:hidden;
    text-decoration:none; transition:.2s; }
  .urgent-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(160,82,45,.15); }
  .urgent-card-img { width:100%; height:100px; object-fit:cover; background:#f0ebe3; }
  .urgent-card-img-placeholder { width:100%; height:100px; background:linear-gradient(135deg,#f5ede3,#ede3d5);
    display:flex; align-items:center; justify-content:center; }
  .urgent-badge { display:inline-flex; align-items:center; gap:4px;
    background:#FF6B35; color:#fff; font-size:9px; font-weight:700;
    padding:3px 8px; border-radius:20px; margin:8px 10px 0; }
  .urgent-name { font-size:12px; font-weight:600; color:var(--pine);
    padding:4px 10px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .urgent-price { display:flex; align-items:center; gap:6px; padding:2px 10px 10px; }
  .urgent-price .disc { font-size:13px; font-weight:700; color:var(--copper); }
  .urgent-price .orig { font-size:10px; color:#aaa; text-decoration:line-through; }

  /* MERCHANT GRID */
  .merchant-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(260px,1fr)); gap:16px; }
  .merchant-card { background:#fff; border-radius:16px; border:1px solid #EDE8DF;
    overflow:hidden; text-decoration:none; transition:.2s; display:block; }
  .merchant-card:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(45,80,22,.12); }
  .merchant-header { background:linear-gradient(135deg, var(--pine), var(--dingley));
    padding:20px 20px 14px; position:relative; }
  .merchant-avatar { width:48px; height:48px; background:rgba(255,255,255,.2);
    border-radius:12px; display:flex; align-items:center; justify-content:center;
    font-size:20px; font-weight:700; color:#fff; margin-bottom:10px; }
  .merchant-name { color:#fff; font-size:15px; font-weight:700; margin:0 0 2px; }
  .merchant-address { color:rgba(255,255,255,.7); font-size:11px; white-space:nowrap;
    overflow:hidden; text-overflow:ellipsis; }
  .merchant-badge { position:absolute; top:12px; right:12px; background:rgba(255,255,255,.2);
    border:1px solid rgba(255,255,255,.3); color:#fff; font-size:9px;
    font-weight:700; padding:3px 8px; border-radius:20px; }
  .merchant-body { padding:14px 16px; }
  .merchant-meta { display:flex; align-items:center; justify-content:space-between; }
  .merchant-stock { font-size:13px; color:var(--dingley); font-weight:600; }
  .merchant-stock span { font-size:20px; font-weight:700; color:var(--pine); margin-right:3px; }
  .merchant-action { background:var(--pine); color:#fff; font-size:12px;
    font-weight:600; padding:7px 14px; border-radius:20px; }

  /* EMPTY STATE */
  .empty { text-align:center; padding:60px 20px; }
  .empty-icon { font-size:48px; margin-bottom:12px; }
  .empty h3 { font-size:17px; font-weight:700; color:var(--pine); margin:0 0 6px; }
  .empty p { font-size:14px; color:#888; margin:0; }

  /* BOTTOM NAV (mobile) */
  .bottom-nav { display:none; position:fixed; bottom:0; left:0; right:0;
    background:var(--pine); border-top:1px solid var(--kombu);
    padding:6px 0 8px; z-index:50; }
  .bottom-nav-inner { display:grid; grid-template-columns:repeat(4,1fr); }
  .bottom-nav a { display:flex; flex-direction:column; align-items:center;
    gap:3px; text-decoration:none; padding:4px 0; }
  .bottom-nav a span { font-size:9px; color:rgba(255,255,255,.55); }
  .bottom-nav a.active span { color:var(--brandy); font-weight:600; }
  .bottom-nav svg { opacity:.55; }
  .bottom-nav a.active svg { opacity:1; }

  @media(max-width:768px) {
    .bottom-nav { display:block; }
    .nav-links { display:none; }
    .search-wrap { max-width:none; }
    .hero { padding:32px 16px 28px; }
    .main { padding:20px 16px 90px; }
    .merchant-grid { grid-template-columns:1fr 1fr; gap:12px; }
    .merchant-header { padding:14px 14px 10px; }
    .merchant-avatar { width:38px; height:38px; font-size:16px; margin-bottom:8px; }
    .merchant-name { font-size:13px; }
  }
  @media(max-width:400px) {
    .merchant-grid { grid-template-columns:1fr; }
  }
</style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar">
  <div class="navbar-inner">
    <a href="{{ route('home') }}" class="logo">
      <div class="logo-box"><span>S</span></div>
      <span class="logo-text">App.<span>Surplus</span></span>
    </a>
    <form action="{{ route('home') }}" method="GET" class="search-wrap">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="white"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
      <input type="text" name="search" placeholder="Cari merchant atau makanan..." value="{{ $search ?? '' }}">
    </form>
    <div class="nav-links">
      @auth
        <a href="{{ route('cart.index') }}" class="nav-btn" style="position:relative">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
          Keranjang
        </a>
        <a href="{{ route('my-orders.index') }}" class="nav-btn">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
          Pesanan
        </a>
        <a href="{{ route('profile.edit') }}" class="nav-btn">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          Profil
        </a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline">
          @csrf
          <button type="submit" class="nav-btn" style="background:none;border:none;cursor:pointer">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Keluar
          </button>
        </form>
      @else
        <a href="{{ route('login') }}" class="nav-btn">Masuk</a>
        <a href="{{ route('register') }}" class="nav-btn" style="background:var(--brandy);color:#fff;padding:7px 16px;border-radius:20px;">Daftar</a>
      @endauth
    </div>
  </div>
</nav>

{{-- HERO --}}
<section class="hero">
  <div class="hero-inner">
    <h1>Makanan Enak, Harga <span>Lebih Hemat</span> 🌿</h1>
    <p>Selamatkan surplus makanan dari restoran & café terdekat. Kurangi limbah, hemat lebih banyak.</p>
    <div class="hero-stats">
      <div class="hero-stat">
        <div class="num">{{ $stores->count() }}</div>
        <div class="lbl">Merchant Aktif</div>
      </div>
      <div class="hero-stat">
        <div class="num">{{ $latestProducts->count() }}</div>
        <div class="lbl">Produk Tersedia</div>
      </div>
      <div class="hero-stat">
        <div class="num">{{ $urgentProducts->count() }}</div>
        <div class="lbl">Segera Habis</div>
      </div>
    </div>
  </div>
</section>

<div class="main">

  {{-- URGENT PRODUCTS --}}
  @if($urgentProducts->count() > 0)
  <div style="margin-bottom:32px">
    <div class="section-header">
      <div class="section-title">
        <span style="color:#FF6B35">⚡</span> Segera Habis
      </div>
      <span style="font-size:12px;color:#FF6B35;font-weight:600;">Sisa &lt; 3 jam!</span>
    </div>
    <div class="urgent-scroll">
      @foreach($urgentProducts as $product)
      <a href="{{ route('product.detail', $product->id) }}" class="urgent-card">
        @if($product->image_url)
          <img src="{{ asset('storage/'.$product->image_url) }}" alt="{{ $product->name }}" class="urgent-card-img">
        @else
          <div class="urgent-card-img-placeholder">
            <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#C4956A"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          </div>
        @endif
        <div class="urgent-badge">
          <svg width="8" height="8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a10 10 0 100 20A10 10 0 0012 2zm1 14.5V16h-2v.5a.5.5 0 01-1 0v-5a.5.5 0 011 0V13h2v-1.5a.5.5 0 011 0v5a.5.5 0 01-1 0z"/></svg>
          {{ \Carbon\Carbon::parse($product->pickup_deadline)->diffForHumans() }}
        </div>
        <div class="urgent-name">{{ $product->name }}</div>
        <div class="urgent-price">
          <span class="disc">Rp {{ number_format($product->dynamic_price ?? $product->discounted_price, 0, ',', '.') }}</span>
          @if(isset($product->original_price) && $product->original_price > ($product->dynamic_price ?? $product->discounted_price))
          <span class="orig">Rp {{ number_format($product->original_price, 0, ',', '.') }}</span>
          @endif
        </div>
      </a>
      @endforeach
    </div>
  </div>
  @endif

  {{-- MERCHANT LIST --}}
  <div>
    <div class="section-header">
      <div class="section-title">
        @if($search) Hasil pencarian "{{ $search }}" @else Merchant Terdekat @endif
      </div>
    </div>

    @if($stores->count() > 0)
    <div class="merchant-grid">
      @foreach($stores as $store)
      <a href="{{ route('store.detail', $store->id) }}" class="merchant-card">
        <div class="merchant-header">
          <div class="merchant-avatar">{{ strtoupper(substr($store->name, 0, 1)) }}</div>
          <p class="merchant-name">{{ $store->name }}</p>
          <p class="merchant-address">📍 {{ $store->address }}</p>
          @if(($store->active_products_count ?? 0) > 0)
          <div class="merchant-badge">✓ Buka</div>
          @else
          <div class="merchant-badge" style="background:rgba(0,0,0,.2)">Kosong</div>
          @endif
        </div>
        <div class="merchant-body">
          <div class="merchant-meta">
            <div class="merchant-stock">
              <span>{{ $store->active_products_count ?? 0 }}</span> produk
            </div>
            <div class="merchant-action">Lihat →</div>
          </div>
        </div>
      </a>
      @endforeach
    </div>
    @else
    <div class="empty">
      <div class="empty-icon">🔍</div>
      <h3>{{ $search ? 'Merchant tidak ditemukan' : 'Belum ada merchant' }}</h3>
      <p>{{ $search ? 'Coba kata kunci lain' : 'Merchant akan muncul setelah diverifikasi admin' }}</p>
    </div>
    @endif
  </div>

</div>

{{-- BOTTOM NAV (mobile) --}}
<nav class="bottom-nav">
  <div class="bottom-nav-inner">
    <a href="{{ route('home') }}" class="active">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="var(--brandy)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
      <span class="active" style="color:var(--brandy)">Beranda</span>
    </a>
    <a href="{{ route('cart.index') }}">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.6)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      <span>Keranjang</span>
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
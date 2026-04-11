<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $product->name }} — App.Surplus</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  :root {
    --pine:#202808; --kombu:#33432B; --dingley:#6A784D;
    --brandy:#DEC59E; --copper:#C4866D; --cream:#F5F0E8;
  }
  * { box-sizing:border-box; }
  body { background:#F7F4EE; font-family:'Inter',system-ui,sans-serif; margin:0; }

  .navbar { background:var(--pine); position:sticky; top:0; z-index:50; box-shadow:0 2px 12px rgba(0,0,0,.25); }
  .navbar-inner { max-width:700px; margin:0 auto; padding:0 20px; height:60px; display:flex; align-items:center; gap:12px; }
  .back-btn { display:flex; align-items:center; gap:6px; color:rgba(255,255,255,.8); text-decoration:none; font-size:14px; flex-shrink:0; }
  .back-btn:hover { color:#fff; }
  .nav-title { color:#fff; font-size:14px; font-weight:600; flex:1; text-align:center; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .nav-cart-btn { color:rgba(255,255,255,.8); text-decoration:none; flex-shrink:0; }
  .nav-cart-btn:hover { color:#fff; }

  .page-wrap { max-width:700px; margin:0 auto; padding:0 0 120px; }

  /* PRODUCT IMAGE */
  .product-img-wrap { width:100%; height:280px; background:linear-gradient(135deg,#f0ebe3,#e8dfd4); position:relative; overflow:hidden; }
  .product-img-wrap img { width:100%; height:100%; object-fit:cover; }
  .product-img-placeholder { width:100%; height:100%; display:flex; align-items:center; justify-content:center; flex-direction:column; gap:8px; }
  .urgency-overlay { position:absolute; top:16px; left:16px; }
  .urgency-badge { display:inline-flex; align-items:center; gap:5px; font-size:11px; font-weight:700; padding:5px 12px; border-radius:20px; }
  .urgency-critical { background:#FF4444; color:#fff; }
  .urgency-high { background:#FF8C00; color:#fff; }
  .urgency-medium { background:#DEC59E; color:#202808; }
  .urgency-low { background:#6A784D; color:#fff; }
  .discount-overlay { position:absolute; top:16px; right:16px; background:var(--pine); color:var(--brandy); font-size:13px; font-weight:700; padding:5px 12px; border-radius:20px; }

  /* MAIN CONTENT */
  .content { background:#fff; margin:0; padding:20px; }
  .store-link { display:inline-flex; align-items:center; gap:6px; background:#F0F4EC; color:var(--dingley); font-size:12px; font-weight:600; padding:5px 12px; border-radius:20px; text-decoration:none; margin-bottom:12px; }
  .store-link:hover { background:#E8EEE2; }
  .product-title { font-size:20px; font-weight:700; color:var(--pine); margin:0 0 8px; line-height:1.3; }
  .product-desc { font-size:14px; color:#666; line-height:1.6; margin:0 0 16px; }

  /* PRICE BLOCK */
  .price-block { background:#F7F4EE; border-radius:14px; padding:16px; margin-bottom:16px; }
  .price-row { display:flex; align-items:center; justify-content:space-between; margin-bottom:6px; }
  .price-label { font-size:12px; color:#888; }
  .price-orig { font-size:14px; color:#bbb; text-decoration:line-through; }
  .price-dynamic { font-size:26px; font-weight:700; color:var(--pine); }
  .price-savings { font-size:12px; color:var(--copper); font-weight:600; }
  .price-dynamic-label { font-size:11px; color:var(--dingley); margin-top:2px; }

  /* INFO GRID */
  .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:16px; }
  .info-item { background:#F7F4EE; border-radius:12px; padding:12px 14px; }
  .info-item-label { font-size:10px; color:#999; text-transform:uppercase; letter-spacing:.05em; margin-bottom:3px; }
  .info-item-value { font-size:14px; font-weight:700; color:var(--pine); }
  .info-item-sub { font-size:11px; color:var(--dingley); margin-top:1px; }

  /* TIMER */
  .timer-block { background:var(--pine); border-radius:14px; padding:14px 16px; margin-bottom:16px; display:flex; align-items:center; gap:14px; }
  .timer-icon { width:40px; height:40px; background:rgba(255,255,255,.1); border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
  .timer-text-label { color:rgba(255,255,255,.65); font-size:11px; margin:0 0 3px; }
  .timer-countdown { color:var(--brandy); font-size:18px; font-weight:700; margin:0; font-family:monospace; }

  /* QTY & ADD TO CART */
  .action-block { padding:16px; border-top:1px solid #F0EDE8; }
  .stock-info { font-size:13px; color:var(--dingley); margin-bottom:12px; }
  .stock-info span { font-weight:700; color:var(--pine); }
  .qty-row { display:flex; align-items:center; gap:12px; margin-bottom:14px; }
  .qty-label { font-size:13px; color:#666; }
  .qty-control { display:flex; align-items:center; gap:0; border:1.5px solid #E0DDD8; border-radius:10px; overflow:hidden; }
  .qty-btn { width:40px; height:40px; background:none; border:none; cursor:pointer; font-size:18px; color:var(--pine); font-weight:700; display:flex; align-items:center; justify-content:center; transition:.15s; }
  .qty-btn:hover { background:#F0EDE8; }
  .qty-btn:disabled { opacity:.35; cursor:not-allowed; }
  .qty-num { width:48px; text-align:center; font-size:15px; font-weight:700; color:var(--pine); border:none; border-left:1.5px solid #E0DDD8; border-right:1.5px solid #E0DDD8; height:40px; }
  .add-cart-btn { width:100%; padding:14px; background:var(--pine); color:var(--brandy); border:none; border-radius:12px; font-size:15px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:.2s; }
  .add-cart-btn:hover { background:var(--kombu); }
  .add-cart-btn:disabled { opacity:.5; cursor:not-allowed; }
  .add-cart-btn.loading { opacity:.7; pointer-events:none; }

  /* TOAST */
  .toast { position:fixed; bottom:90px; left:50%; transform:translateX(-50%) translateY(20px);
    background:var(--pine); color:#fff; padding:10px 20px; border-radius:22px;
    font-size:13px; font-weight:600; opacity:0; transition:.3s; z-index:100; white-space:nowrap; }
  .toast.show { opacity:1; transform:translateX(-50%) translateY(0); }

  /* BOTTOM NAV */
  .bottom-nav { position:fixed; bottom:0; left:0; right:0; background:var(--pine); border-top:1px solid var(--kombu); padding:6px 0 8px; z-index:50; }
  .bottom-nav-inner { display:grid; grid-template-columns:repeat(4,1fr); max-width:700px; margin:0 auto; }
  .bottom-nav a { display:flex; flex-direction:column; align-items:center; gap:3px; text-decoration:none; padding:4px 0; }
  .bottom-nav a span { font-size:9px; color:rgba(255,255,255,.55); }
  .bottom-nav a.active span { color:var(--brandy); font-weight:600; }

  @media(min-width:768px) {
    .page-wrap { padding-bottom:40px; }
    .product-img-wrap { height:360px; }
    .action-block { padding:20px; }
  }
</style>
</head>
<body>

<nav class="navbar">
  <div class="navbar-inner">
    <a href="{{ url()->previous() }}" class="back-btn">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      Kembali
    </a>
    <span class="nav-title">{{ $product->name }}</span>
    <a href="{{ route('cart.index') }}" class="nav-cart-btn">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    </a>
  </div>
</nav>

<div class="page-wrap">

  {{-- PRODUCT IMAGE --}}
  <div class="product-img-wrap">
    @if($product->image_url)
      <img src="{{ asset('storage/'.$product->image_url) }}" alt="{{ $product->name }}">
    @else
      <div class="product-img-placeholder">
        <svg width="56" height="56" fill="none" viewBox="0 0 24 24" stroke="#C4866D"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <span style="color:#C4866D;font-size:12px">Tidak ada foto</span>
      </div>
    @endif

    {{-- Urgency badge --}}
    <div class="urgency-overlay">
      @php $level = $product->urgency_level; @endphp
      @if($level === 'critical')
        <span class="urgency-badge urgency-critical">🔴 Hampir habis!</span>
      @elseif($level === 'high')
        <span class="urgency-badge urgency-high">⚡ Segera habis</span>
      @elseif($level === 'medium')
        <span class="urgency-badge urgency-medium">🕐 Terbatas</span>
      @else
        <span class="urgency-badge urgency-low">✓ Tersedia</span>
      @endif
    </div>

    {{-- Discount badge --}}
    @if($product->discount_percentage > 0)
    <div class="discount-overlay">-{{ round($product->discount_percentage) }}%</div>
    @endif
  </div>

  {{-- MAIN CONTENT --}}
  <div class="content">

    {{-- Store link --}}
    @if($product->store)
    <a href="{{ route('store.detail', $product->store->id) }}" class="store-link">
      <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
      {{ $product->store->name }}
    </a>
    @endif

    <h1 class="product-title">{{ $product->name }}</h1>
    @if($product->description)
      <p class="product-desc">{{ $product->description }}</p>
    @endif

    {{-- PRICE BLOCK --}}
    <div class="price-block">
      <div class="price-row">
        <span class="price-label">Harga normal</span>
        <span class="price-orig">Rp {{ number_format($product->original_price, 0, ',', '.') }}</span>
      </div>
      <div style="display:flex;align-items:baseline;gap:10px;justify-content:space-between">
        <div>
          <div class="price-dynamic">Rp {{ number_format($product->dynamic_price, 0, ',', '.') }}</div>
          <div class="price-dynamic-label">🤖 Harga AI — menyesuaikan otomatis</div>
        </div>
        @php $savings = $product->original_price - $product->dynamic_price; @endphp
        @if($savings > 0)
        <div style="text-align:right">
          <div class="price-savings">Hemat Rp {{ number_format($savings, 0, ',', '.') }}</div>
          <div style="font-size:10px;color:#aaa">( {{ round($product->discount_percentage) }}% off )</div>
        </div>
        @endif
      </div>
    </div>

    {{-- INFO GRID --}}
    <div class="info-grid">
      <div class="info-item">
        <div class="info-item-label">Stok Tersisa</div>
        <div class="info-item-value">{{ $product->available_stock }} {{ $product->unit ?? 'pcs' }}</div>
        @if($product->reserved_stock > 0)
          <div class="info-item-sub">{{ $product->reserved_stock }} sedang di keranjang</div>
        @endif
      </div>
      <div class="info-item">
        <div class="info-item-label">Kategori</div>
        <div class="info-item-value">{{ $product->category ?? '—' }}</div>
      </div>
      @if($product->expiry_date)
      <div class="info-item">
        <div class="info-item-label">Kedaluwarsa</div>
        <div class="info-item-value">{{ \Carbon\Carbon::parse($product->expiry_date)->format('d M Y') }}</div>
      </div>
      @endif
      <div class="info-item">
        <div class="info-item-label">Ambil sebelum</div>
        <div class="info-item-value">{{ \Carbon\Carbon::parse($product->pickup_deadline)->format('H:i') }}</div>
        <div class="info-item-sub">{{ \Carbon\Carbon::parse($product->pickup_deadline)->format('d M Y') }}</div>
      </div>
    </div>

    {{-- COUNTDOWN TIMER --}}
    <div class="timer-block">
      <div class="timer-icon">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="var(--brandy)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div>
        <p class="timer-text-label">Batas waktu pengambilan</p>
        <p class="timer-countdown" id="countdown">--:--:--</p>
      </div>
      <div style="margin-left:auto;text-align:right">
        <div style="color:rgba(255,255,255,.6);font-size:10px">Semakin dekat deadline</div>
        <div style="color:var(--brandy);font-size:10px;font-weight:600">harga makin turun 🤖</div>
      </div>
    </div>

    {{-- ECO INFO --}}
    <div style="background:#F0F4EC;border-radius:12px;padding:12px 14px;margin-bottom:16px;display:flex;align-items:center;gap:10px;">
      <span style="font-size:22px">🌿</span>
      <div>
        <div style="font-size:12px;font-weight:700;color:var(--pine)">Kamu membantu lingkungan!</div>
        <div style="font-size:11px;color:var(--dingley)">Membeli produk ini mengurangi food waste dari {{ $product->store->name ?? 'merchant' }} ini.</div>
      </div>
    </div>

  </div>

  {{-- ACTION BLOCK --}}
  @auth
  <div class="action-block" style="background:#fff">
    <div class="stock-info">
      Stok tersedia: <span>{{ $product->available_stock }} {{ $product->unit ?? 'pcs' }}</span>
    </div>

    @if($product->available_stock > 0 && $product->urgency_level !== 'expired')
    <div class="qty-row">
      <span class="qty-label">Jumlah:</span>
      <div class="qty-control">
        <button class="qty-btn" id="qty-minus" onclick="changeQty(-1)" disabled>−</button>
        <input type="number" class="qty-num" id="qty-input" value="1" min="1" max="{{ $product->available_stock }}" readonly>
        <button class="qty-btn" id="qty-plus" onclick="changeQty(1)">+</button>
      </div>
      <span style="font-size:13px;color:#888">× Rp <span id="unit-price">{{ number_format($product->dynamic_price, 0, ',', '.') }}</span></span>
    </div>

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;padding:10px 14px;background:#F7F4EE;border-radius:10px;">
      <span style="font-size:13px;color:#666">Total:</span>
      <span style="font-size:17px;font-weight:700;color:var(--pine)" id="total-price">Rp {{ number_format($product->dynamic_price, 0, ',', '.') }}</span>
    </div>

    <form id="add-cart-form" action="{{ route('cart.add') }}" method="POST">
      @csrf
      <input type="hidden" name="product_id" value="{{ $product->id }}">
      <input type="hidden" name="quantity" id="form-qty" value="1">
      <button type="submit" class="add-cart-btn" id="add-cart-btn">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        Tambah ke Keranjang
      </button>
    </form>
    @else
    <div style="text-align:center;padding:16px;background:#FFF5F5;border-radius:12px;color:#cc4444;font-weight:600;font-size:14px;">
      {{ $product->urgency_level === 'expired' ? '⏰ Produk sudah expired' : '😔 Stok habis' }}
    </div>
    @endif
  </div>
  @else
  <div class="action-block" style="background:#fff;text-align:center;">
    <p style="color:#888;font-size:14px;margin-bottom:14px">Login untuk menambahkan ke keranjang</p>
    <a href="{{ route('login') }}" style="display:block;padding:14px;background:var(--pine);color:var(--brandy);border-radius:12px;font-size:15px;font-weight:700;text-decoration:none;">Masuk Sekarang</a>
  </div>
  @endauth

</div>

{{-- TOAST --}}
<div class="toast" id="toast"></div>

{{-- BOTTOM NAV --}}
<nav class="bottom-nav">
  <div class="bottom-nav-inner">
    <a href="{{ route('home') }}">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.6)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
      <span>Beranda</span>
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

<script>
const dynamicPrice = {{ $product->dynamic_price }};
const maxStock = {{ $product->available_stock }};
let qty = 1;

function changeQty(delta) {
  qty = Math.max(1, Math.min(maxStock, qty + delta));
  document.getElementById('qty-input').value = qty;
  document.getElementById('form-qty').value = qty;
  document.getElementById('qty-minus').disabled = qty <= 1;
  document.getElementById('qty-plus').disabled = qty >= maxStock;
  const total = qty * dynamicPrice;
  document.getElementById('total-price').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

// Countdown timer
const deadline = new Date('{{ \Carbon\Carbon::parse($product->pickup_deadline)->toIso8601String() }}');
function updateCountdown() {
  const now = new Date();
  const diff = deadline - now;
  if (diff <= 0) {
    document.getElementById('countdown').textContent = 'EXPIRED';
    document.getElementById('countdown').style.color = '#FF4444';
    return;
  }
  const h = Math.floor(diff / 3600000);
  const m = Math.floor((diff % 3600000) / 60000);
  const s = Math.floor((diff % 60000) / 1000);
  document.getElementById('countdown').textContent =
    String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
}
updateCountdown();
setInterval(updateCountdown, 1000);

// Add to cart feedback
document.getElementById('add-cart-form')?.addEventListener('submit', function(e) {
  const btn = document.getElementById('add-cart-btn');
  btn.classList.add('loading');
  btn.innerHTML = '<svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="animation:spin 1s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Menambahkan...';
});
</script>
<style>
@keyframes spin { to { transform:rotate(360deg); } }
</style>
</body>
</html>
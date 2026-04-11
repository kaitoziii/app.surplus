<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>App.Surplus — Selamatkan Makanan</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
* { font-family: 'Plus Jakarta Sans', sans-serif; }
:root {
  --pine:    #202808;
  --kombu:   #33432B;
  --dingley: #6A784D;
  --brandy:  #DEC59E;
  --copper:  #C4866D;
  --cream:   #f5f2ec;
}
body { background: var(--cream); }

.navbar { background: var(--pine); }
.search-bar { background: var(--kombu); }

.card-store {
  background: #fff;
  border-radius: 14px;
  border: 1px solid #e8e4dc;
  overflow: hidden;
  transition: box-shadow .2s, transform .2s;
}
.card-store:hover { box-shadow: 0 6px 24px rgba(32,40,8,.10); transform: translateY(-2px); }

.card-product {
  background: #fff;
  border-radius: 14px;
  border: 1px solid #e8e4dc;
  overflow: hidden;
  transition: box-shadow .2s, transform .2s;
}
.card-product:hover { box-shadow: 0 6px 24px rgba(32,40,8,.10); transform: translateY(-2px); }

.badge-critical { background: rgba(196,134,109,.15); color: #a05030; }
.badge-high     { background: rgba(222,197,158,.25); color: #8a6a2a; }
.badge-medium   { background: rgba(106,120,77,.12);  color: #3a5020; }
.badge-low      { background: rgba(51,67,43,.08);    color: var(--kombu); }

.btn-primary {
  background: var(--dingley);
  color: #fff;
  border-radius: 8px;
  font-weight: 600;
  font-size: 13px;
  padding: 8px 16px;
  transition: opacity .2s;
}
.btn-primary:hover { opacity: .88; }

.section-title {
  font-size: 16px;
  font-weight: 700;
  color: var(--pine);
}

.tag-urgent {
  background: rgba(196,134,109,.12);
  color: var(--copper);
  font-size: 10px;
  font-weight: 700;
  padding: 3px 8px;
  border-radius: 20px;
  animation: pulse 2s infinite;
}
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.6} }

.store-badge {
  background: rgba(106,120,77,.1);
  color: var(--dingley);
  font-size: 10px;
  font-weight: 600;
  padding: 2px 8px;
  border-radius: 20px;
}
</style>
</head>
<body>

{{-- ===== NAVBAR ===== --}}
<nav class="navbar sticky top-0 z-50 px-4 py-3">
  <div class="max-w-6xl mx-auto flex items-center justify-between gap-3">

    {{-- Logo --}}
    <a href="/" class="flex items-center gap-2 flex-shrink-0">
      <div style="width:30px;height:30px;background:var(--dingley);border-radius:7px;" class="flex items-center justify-center">
        <span style="color:var(--brandy);font-size:13px;font-weight:700;">S</span>
      </div>
      <span style="color:var(--brandy);font-size:14px;font-weight:700;">App.Surplus</span>
    </a>

    {{-- Search --}}
    <form method="GET" action="/" class="flex-1 max-w-md">
      <div class="relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color:var(--dingley)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input type="text" name="search" value="{{ $search ?? '' }}"
               placeholder="Mau selamatkan produk apa hari ini?"
               style="background:var(--kombu);color:var(--brandy);border:1px solid var(--dingley);border-radius:10px;padding:8px 12px 8px 36px;width:100%;font-size:12px;"
               class="placeholder-shown:text-opacity-60 focus:outline-none">
      </div>
    </form>

    {{-- Icons --}}
    <div class="flex items-center gap-3 flex-shrink-0">
      <a href="/cart" class="relative flex items-center justify-center w-8 h-8 rounded-lg" style="background:var(--kombu);">
        <svg class="w-4 h-4" style="color:var(--brandy)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
      </a>
      @auth
      <a href="/profile" class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold"
         style="background:var(--dingley);color:var(--brandy);">
        {{ substr(auth()->user()->name, 0, 1) }}
      </a>
      @else
      <a href="/login" class="btn-primary text-xs px-3 py-1.5">Masuk</a>
      @endauth
    </div>

  </div>
</nav>

{{-- ===== HERO SECTION ===== --}}
<div style="background:linear-gradient(135deg,var(--pine),var(--kombu));padding:32px 16px;">
  <div class="max-w-6xl mx-auto">
    <p style="color:var(--dingley);font-size:12px;font-weight:600;letter-spacing:1px;margin-bottom:6px;">SELAMATKAN MAKANAN, HEMAT LEBIH BANYAK</p>
    <h1 style="color:var(--brandy);font-size:26px;font-weight:700;margin-bottom:8px;line-height:1.3;">
      Temukan Makanan Surplus<br>di Sekitar Kamu 🌿
    </h1>
    <p style="color:var(--dingley);font-size:13px;margin-bottom:20px;">
      Beli makanan berkualitas dengan harga diskon — bantu kurangi food waste!
    </p>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-3 max-w-sm">
      <div style="background:rgba(106,120,77,.2);border-radius:10px;padding:10px;text-align:center;">
        <p style="color:var(--brandy);font-size:18px;font-weight:700;margin:0;">{{ \App\Models\Store::where('status','approved')->count() }}</p>
        <p style="color:var(--dingley);font-size:10px;margin:0;">Merchant</p>
      </div>
      <div style="background:rgba(106,120,77,.2);border-radius:10px;padding:10px;text-align:center;">
        <p style="color:var(--brandy);font-size:18px;font-weight:700;margin:0;">{{ \App\Models\Product::where('is_available',true)->where('pickup_deadline','>',now())->count() }}</p>
        <p style="color:var(--dingley);font-size:10px;margin:0;">Produk</p>
      </div>
      <div style="background:rgba(106,120,77,.2);border-radius:10px;padding:10px;text-align:center;">
        <p style="color:var(--brandy);font-size:18px;font-weight:700;margin:0;">{{ \App\Models\Transaction::where('status','picked_up')->count() }}</p>
        <p style="color:var(--dingley);font-size:10px;margin:0;">Diselamatkan</p>
      </div>
    </div>
  </div>
</div>

<div class="max-w-6xl mx-auto px-4 py-6 space-y-8">

  {{-- ===== URGENT PRODUCTS ===== --}}
  @if($urgentProducts->count() > 0)
  <section>
    <div class="flex items-center justify-between mb-3">
      <div class="flex items-center gap-2">
        <span class="section-title">⚡ Segera Habis</span>
        <span class="tag-urgent">DEADLINE DEKAT</span>
      </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
      @foreach($urgentProducts as $product)
      @php
        $urgency = $product->urgency_level;
        $minsLeft = $product->time_remaining_minutes;
        $hoursLeft = floor($minsLeft / 60);
        $minRem = $minsLeft % 60;
        $timeStr = $minsLeft > 60 ? "{$hoursLeft}j {$minRem}m" : "{$minsLeft}m";
      @endphp
      <a href="{{ route('product.detail', $product->id) }}" class="card-product block">
        {{-- Image --}}
        <div class="relative" style="height:120px;overflow:hidden;background:#f0ede6;">
          @if($product->image_url)
            <img src="{{ asset('storage/'.$product->image_url) }}"
                 class="w-full h-full object-cover">
          @else
            <div class="w-full h-full flex items-center justify-center">
              <svg class="w-10 h-10" style="color:var(--brandy)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
          @endif
          {{-- Discount badge --}}
          @if($product->discount_percentage > 0)
          <div class="absolute top-2 left-2 badge-critical" style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;">
            -{{ round($product->discount_percentage) }}%
          </div>
          @endif
          {{-- Timer --}}
          <div class="absolute bottom-0 left-0 right-0 px-2 py-1.5" style="background:rgba(32,40,8,.75);">
            <p style="color:var(--brandy);font-size:10px;font-weight:600;margin:0;">⏱ {{ $timeStr }}</p>
          </div>
        </div>
        {{-- Info --}}
        <div style="padding:10px;">
          <p style="font-size:12px;font-weight:700;color:var(--pine);margin:0 0 2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $product->name }}</p>
          <p style="font-size:10px;color:var(--dingley);margin:0 0 6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $product->store->name ?? '-' }}</p>
          <div class="flex items-center justify-between">
            <div>
              <p style="font-size:10px;color:#aaa;text-decoration:line-through;margin:0;">Rp {{ number_format($product->original_price,0,',','.') }}</p>
              <p style="font-size:13px;font-weight:700;color:var(--dingley);margin:0;">Rp {{ number_format($product->dynamic_price,0,',','.') }}</p>
            </div>
            <span style="font-size:10px;color:var(--dingley);background:rgba(106,120,77,.1);padding:2px 6px;border-radius:6px;">{{ $product->available_stock }} sisa</span>
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </section>
  @endif

  {{-- ===== STORES ===== --}}
  <section>
    <div class="flex items-center justify-between mb-3">
      <span class="section-title">🏪 Merchant Terdekat</span>
    </div>

    @if($stores->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      @foreach($stores as $store)
      <a href="/store/{{ $store->id }}" class="card-store flex items-center gap-4 p-4">
        {{-- Avatar --}}
        <div style="width:52px;height:52px;background:linear-gradient(135deg,var(--kombu),var(--dingley));border-radius:12px;flex-shrink:0;" class="flex items-center justify-center">
          <span style="color:var(--brandy);font-size:18px;font-weight:700;">{{ substr($store->name,0,1) }}</span>
        </div>
        {{-- Info --}}
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2 mb-1">
            <p style="font-size:14px;font-weight:700;color:var(--pine);margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $store->name }}</p>
            <span class="store-badge">Buka</span>
          </div>
          <p style="font-size:11px;color:var(--dingley);margin:0 0 4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">📍 {{ $store->address }}</p>
          <div class="flex items-center gap-2">
            <span style="font-size:11px;color:var(--dingley);background:rgba(106,120,77,.1);padding:2px 8px;border-radius:20px;font-weight:600;">
              {{ $store->active_products_count }} produk tersedia
            </span>
          </div>
        </div>
        {{-- Arrow --}}
        <svg class="w-4 h-4 flex-shrink-0" style="color:var(--dingley)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </a>
      @endforeach
    </div>
    @else
    <div style="background:#fff;border-radius:14px;border:1px solid #e8e4dc;padding:40px;text-align:center;">
      <p style="font-size:32px;margin-bottom:8px;">🌿</p>
      <p style="font-weight:700;color:var(--pine);margin:0 0 4px;">Belum ada merchant tersedia</p>
      <p style="font-size:12px;color:var(--dingley);margin:0;">
        @if($search) Coba cari dengan kata kunci lain @else Merchant sedang belum buka @endif
      </p>
    </div>
    @endif
  </section>

  {{-- ===== LATEST PRODUCTS ===== --}}
  @if($latestProducts->count() > 0)
  <section>
    <div class="flex items-center justify-between mb-3">
      <span class="section-title">🛍️ Semua Produk</span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
      @foreach($latestProducts as $product)
      <a href="{{ route('product.detail', $product->id) }}" class="card-product block">
        <div class="relative" style="height:130px;overflow:hidden;background:#f0ede6;">
          @if($product->image_url)
            <img src="{{ asset('storage/'.$product->image_url) }}" class="w-full h-full object-cover">
          @else
            <div class="w-full h-full flex items-center justify-center">
              <svg class="w-10 h-10" style="color:var(--brandy)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
          @endif
          @if($product->discount_percentage > 0)
          <div class="absolute top-2 left-2" style="background:var(--copper);color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;">
            -{{ round($product->discount_percentage) }}%
          </div>
          @endif
        </div>
        <div style="padding:10px;">
          <p style="font-size:12px;font-weight:700;color:var(--pine);margin:0 0 2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $product->name }}</p>
          <p style="font-size:10px;color:var(--dingley);margin:0 0 6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $product->store->name ?? '-' }}</p>
          <div class="flex items-center justify-between">
            <div>
              @if($product->discount_percentage > 0)
              <p style="font-size:10px;color:#aaa;text-decoration:line-through;margin:0;">Rp {{ number_format($product->original_price,0,',','.') }}</p>
              @endif
              <p style="font-size:13px;font-weight:700;color:var(--dingley);margin:0;">Rp {{ number_format($product->dynamic_price,0,',','.') }}</p>
            </div>
            @php
              $urgency = $product->urgency_level;
              $badgeClass = $urgency === 'critical' ? 'badge-critical' : ($urgency === 'high' ? 'badge-high' : 'badge-low');
              $badgeText = $urgency === 'critical' ? '🔴 Kritis' : ($urgency === 'high' ? '🟠 Segera' : '🟢 Aman');
            @endphp
            <span class="{{ $badgeClass }}" style="font-size:9px;font-weight:600;padding:2px 6px;border-radius:6px;">{{ $badgeText }}</span>
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </section>
  @endif

</div>

{{-- ===== BOTTOM NAV (Mobile) ===== --}}
<div class="md:hidden fixed bottom-0 left-0 right-0 z-50" style="background:var(--pine);border-top:1px solid var(--kombu);padding:8px 0;">
  <div class="grid grid-cols-3 gap-0">
    <a href="/" class="flex flex-col items-center gap-1 py-1">
      <svg class="w-5 h-5" style="color:var(--brandy)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
      <span style="font-size:9px;color:var(--brandy);font-weight:600;">Beranda</span>
    </a>
    <a href="/cart" class="flex flex-col items-center gap-1 py-1">
      <svg class="w-5 h-5" style="color:var(--dingley)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      <span style="font-size:9px;color:var(--dingley);">Keranjang</span>
    </a>
    <a href="/history" class="flex flex-col items-center gap-1 py-1">
      <svg class="w-5 h-5" style="color:var(--dingley)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      <span style="font-size:9px;color:var(--dingley);">Pesanan</span>
    </a>
  </div>
</div>

<div class="md:hidden h-16"></div>

</body>
</html>
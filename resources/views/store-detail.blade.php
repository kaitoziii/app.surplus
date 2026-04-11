<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $store->name }} — App.Surplus</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
* { font-family: 'Plus Jakarta Sans', sans-serif; }
:root {
  --pine:#202808; --kombu:#33432B; --dingley:#6A784D;
  --brandy:#DEC59E; --copper:#C4866D; --cream:#f5f2ec;
}
body { background: var(--cream); }
.card-product { background:#fff; border-radius:14px; border:1px solid #e8e4dc; overflow:hidden; transition:box-shadow .2s,transform .2s; }
.card-product:hover { box-shadow:0 6px 24px rgba(32,40,8,.10); transform:translateY(-2px); }
.badge-critical { background:rgba(196,134,109,.15); color:#a05030; }
.badge-high     { background:rgba(222,197,158,.25);  color:#8a6a2a; }
.badge-low      { background:rgba(106,120,77,.12);   color:#3a5020; }
</style>
</head>
<body>

{{-- NAVBAR --}}
<nav style="background:var(--pine);padding:12px 16px;" class="sticky top-0 z-50">
  <div class="max-w-6xl mx-auto flex items-center gap-3">
    <a href="/" style="color:var(--dingley);display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      Kembali
    </a>
    <span style="color:var(--dingley);">|</span>
    <span style="color:var(--brandy);font-size:13px;font-weight:700;">{{ $store->name }}</span>
    <div class="ml-auto flex items-center gap-2">
      <a href="/cart" style="background:var(--kombu);border-radius:8px;padding:6px 8px;display:flex;align-items:center;">
        <svg class="w-4 h-4" style="color:var(--brandy)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
      </a>
    </div>
  </div>
</nav>

{{-- STORE HEADER --}}
<div style="background:linear-gradient(135deg,var(--pine),var(--kombu));padding:24px 16px;">
  <div class="max-w-6xl mx-auto flex items-center gap-4">
    <div style="width:60px;height:60px;background:var(--dingley);border-radius:14px;flex-shrink:0;" class="flex items-center justify-center">
      <span style="color:var(--brandy);font-size:24px;font-weight:700;">{{ substr($store->name,0,1) }}</span>
    </div>
    <div>
      <h1 style="color:var(--brandy);font-size:20px;font-weight:700;margin:0 0 4px;">{{ $store->name }}</h1>
      <p style="color:var(--dingley);font-size:12px;margin:0 0 6px;">📍 {{ $store->address }}</p>
      <div class="flex items-center gap-2">
        <span style="background:rgba(106,120,77,.3);color:var(--brandy);font-size:10px;font-weight:600;padding:2px 10px;border-radius:20px;">
          {{ $products->count() }} produk tersedia
        </span>
        <span style="background:rgba(106,120,77,.3);color:var(--brandy);font-size:10px;font-weight:600;padding:2px 10px;border-radius:20px;">
          ✅ Terverifikasi
        </span>
      </div>
    </div>
  </div>
</div>

{{-- PRODUCTS --}}
<div class="max-w-6xl mx-auto px-4 py-6">

  @if($products->count() > 0)
  <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
    @foreach($products as $product)
    @php
      $urgency = $product->urgency_level;
      $minsLeft = $product->time_remaining_minutes;
      $hoursLeft = floor($minsLeft/60); $minRem = $minsLeft%60;
      $timeStr = $minsLeft > 60 ? "{$hoursLeft}j {$minRem}m" : "{$minsLeft}m";
    @endphp
    <a href="{{ route('product.detail', $product->id) }}" class="card-product block">
      <div class="relative" style="height:130px;overflow:hidden;background:#f0ede6;">
        @if($product->image_url)
          <img src="{{ asset('storage/'.$product->image_url) }}" class="w-full h-full object-cover">
        @else
          <div class="w-full h-full flex items-center justify-center">
            <svg class="w-10 h-10" style="color:var(--brandy)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          </div>
        @endif
        @if($product->discount_percentage > 0)
        <div class="absolute top-2 left-2" style="background:var(--copper);color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;">
          -{{ round($product->discount_percentage) }}%
        </div>
        @endif
        <div class="absolute bottom-0 left-0 right-0 px-2 py-1" style="background:rgba(32,40,8,.7);">
          <p style="color:var(--brandy);font-size:10px;font-weight:600;margin:0;">⏱ {{ $timeStr }}</p>
        </div>
      </div>
      <div style="padding:10px;">
        <p style="font-size:12px;font-weight:700;color:var(--pine);margin:0 0 6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $product->name }}</p>
        <div class="flex items-center justify-between">
          <div>
            @if($product->discount_percentage > 0)
            <p style="font-size:10px;color:#aaa;text-decoration:line-through;margin:0;">Rp {{ number_format($product->original_price,0,',','.') }}</p>
            @endif
            <p style="font-size:13px;font-weight:700;color:var(--dingley);margin:0;">Rp {{ number_format($product->dynamic_price,0,',','.') }}</p>
          </div>
          <span style="font-size:10px;color:var(--dingley);background:rgba(106,120,77,.1);padding:2px 6px;border-radius:6px;">{{ $product->available_stock }} sisa</span>
        </div>
      </div>
    </a>
    @endforeach
  </div>

  @else
  <div style="background:#fff;border-radius:14px;border:1px solid #e8e4dc;padding:48px;text-align:center;">
    <p style="font-size:32px;margin-bottom:8px;">🌿</p>
    <p style="font-weight:700;color:var(--pine);margin:0 0 4px;">Belum ada produk tersedia</p>
    <p style="font-size:12px;color:var(--dingley);margin:0;">Merchant sedang tidak memiliki produk aktif</p>
  </div>
  @endif

</div>

<div class="md:hidden h-16"></div>

{{-- BOTTOM NAV --}}
<div class="md:hidden fixed bottom-0 left-0 right-0 z-50" style="background:var(--pine);border-top:1px solid var(--kombu);padding:8px 0;">
  <div class="grid grid-cols-3 gap-0">
    <a href="/" class="flex flex-col items-center gap-1 py-1">
      <svg class="w-5 h-5" style="color:var(--dingley)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
      <span style="font-size:9px;color:var(--dingley);">Beranda</span>
    </a>
    <a href="/cart" class="flex flex-col items-center gap-1 py-1">
      <svg class="w-5 h-5" style="color:var(--brandy)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      <span style="font-size:9px;color:var(--brandy);font-weight:600;">Keranjang</span>
    </a>
    <a href="/history" class="flex flex-col items-center gap-1 py-1">
      <svg class="w-5 h-5" style="color:var(--dingley)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      <span style="font-size:9px;color:var(--dingley);">Pesanan</span>
    </a>
  </div>
</div>

</body>
</html>
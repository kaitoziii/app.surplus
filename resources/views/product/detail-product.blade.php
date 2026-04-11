<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $product->name }} — App.Surplus</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
* { font-family: 'Plus Jakarta Sans', sans-serif; }
:root { --pine:#202808; --kombu:#33432B; --dingley:#6A784D; --brandy:#DEC59E; --copper:#C4866D; --cream:#f5f2ec; }
body { background:var(--cream); }
.btn-cart { background:var(--dingley); color:#fff; border-radius:10px; font-weight:700; font-size:14px; padding:12px; width:100%; transition:opacity .2s; border:none; cursor:pointer; }
.btn-cart:hover { opacity:.88; }
.btn-cart:disabled { background:#ccc; cursor:not-allowed; }
</style>
</head>
<body>

{{-- NAVBAR --}}
<nav style="background:var(--pine);padding:12px 16px;" class="sticky top-0 z-50">
  <div class="max-w-5xl mx-auto flex items-center gap-3">
    <a href="javascript:history.back()" style="color:var(--dingley);display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      Kembali
    </a>
    <div class="ml-auto">
      <a href="/cart" style="background:var(--kombu);border-radius:8px;padding:6px 8px;display:flex;align-items:center;">
        <svg class="w-4 h-4" style="color:var(--brandy)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
      </a>
    </div>
  </div>
</nav>

@php
  $stockAvailable = $product->available_stock > 0;
  $deadlineISO = $product->pickup_deadline ? $product->pickup_deadline->format('c') : null;
  $urgency = $product->urgency_level;
  $urgencyColor = $urgency === 'critical' ? '#C4866D' : ($urgency === 'high' ? '#b08030' : '#6A784D');
  $urgencyLabel = $urgency === 'critical' ? '🔴 Kritis — Segera Habis!' : ($urgency === 'high' ? '🟠 Hampir Habis' : '🟢 Masih Aman');
@endphp

{{-- FLASH MESSAGES --}}
@if(session('success'))
<div class="max-w-5xl mx-auto mt-3 px-4">
  <div style="background:rgba(106,120,77,.1);border:1px solid var(--dingley);border-radius:10px;padding:10px 14px;color:var(--kombu);font-size:13px;">
    ✅ {{ session('success') }}
  </div>
</div>
@endif
@if(session('error'))
<div class="max-w-5xl mx-auto mt-3 px-4">
  <div style="background:rgba(196,134,109,.1);border:1px solid var(--copper);border-radius:10px;padding:10px 14px;color:var(--copper);font-size:13px;">
    ❌ {{ session('error') }}
  </div>
</div>
@endif

<div class="max-w-5xl mx-auto px-4 py-5">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- IMAGE --}}
    <div>
      <div class="relative" style="border-radius:16px;overflow:hidden;background:#f0ede6;height:300px;">
        @if($product->image_url)
          <img src="{{ asset('storage/'.$product->image_url) }}" class="w-full h-full object-cover">
        @else
          <div class="w-full h-full flex items-center justify-center">
            <svg class="w-20 h-20" style="color:var(--brandy)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
          </div>
        @endif

        {{-- Urgency Banner --}}
        <div class="absolute bottom-0 left-0 right-0 px-4 py-2.5" style="background:rgba(32,40,8,.82);">
          <div class="flex items-center justify-between">
            <p style="color:var(--brandy);font-size:11px;font-weight:600;margin:0;">⏱ Sisa waktu:</p>
            <p id="countdown" style="color:var(--brandy);font-size:13px;font-weight:700;margin:0;">--:--:--</p>
          </div>
        </div>

        {{-- Discount Badge --}}
        @if($product->discount_percentage > 0)
        <div class="absolute top-3 left-3" style="background:var(--copper);color:#fff;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;">
          DISKON {{ round($product->discount_percentage) }}%
        </div>
        @endif
      </div>

      {{-- Store Info --}}
      <a href="/store/{{ $product->store->id }}" style="background:#fff;border-radius:12px;border:1px solid #e8e4dc;padding:12px 14px;margin-top:12px;display:flex;align-items:center;gap:12px;">
        <div style="width:40px;height:40px;background:linear-gradient(135deg,var(--kombu),var(--dingley));border-radius:10px;flex-shrink:0;" class="flex items-center justify-center">
          <span style="color:var(--brandy);font-size:16px;font-weight:700;">{{ substr($product->store->name ?? 'S', 0, 1) }}</span>
        </div>
        <div>
          <p style="font-size:13px;font-weight:700;color:var(--pine);margin:0;">{{ $product->store->name ?? '-' }}</p>
          <p style="font-size:11px;color:var(--dingley);margin:0;">📍 {{ $product->store->address ?? '-' }}</p>
        </div>
        <svg class="w-4 h-4 ml-auto flex-shrink-0" style="color:var(--dingley)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>

    {{-- DETAIL --}}
    <div style="background:#fff;border-radius:16px;border:1px solid #e8e4dc;padding:20px;">

      {{-- Title & Urgency --}}
      <div class="mb-3">
        <h1 style="font-size:20px;font-weight:700;color:var(--pine);margin:0 0 6px;">{{ $product->name }}</h1>
        <span style="background:{{ $urgency === 'critical' ? 'rgba(196,134,109,.15)' : 'rgba(106,120,77,.12)' }};color:{{ $urgencyColor }};font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;">
          {{ $urgencyLabel }}
        </span>
      </div>

      {{-- Price --}}
      <div style="background:var(--cream);border-radius:10px;padding:12px 14px;margin-bottom:14px;">
        @if($product->discount_percentage > 0)
        <p style="font-size:12px;color:#aaa;text-decoration:line-through;margin:0;">Rp {{ number_format($product->original_price,0,',','.') }}</p>
        @endif
        <p style="font-size:24px;font-weight:700;color:var(--dingley);margin:0;">Rp {{ number_format($product->dynamic_price,0,',','.') }}</p>
        @if($product->discount_percentage > 0)
        <p style="font-size:11px;color:var(--copper);margin:2px 0 0;font-weight:600;">Hemat Rp {{ number_format($product->original_price - $product->dynamic_price,0,',','.') }}</p>
        @endif
      </div>

      {{-- Info Grid --}}
      <div class="grid grid-cols-2 gap-2 mb-4">
        <div style="background:var(--cream);border-radius:8px;padding:8px 10px;">
          <p style="font-size:10px;color:var(--dingley);margin:0 0 2px;font-weight:600;">STOK</p>
          <p style="font-size:14px;font-weight:700;color:{{ $stockAvailable ? 'var(--dingley)' : 'var(--copper)' }};margin:0;">
            {{ $product->available_stock }} {{ $product->unit ?? 'pcs' }}
          </p>
        </div>
        <div style="background:var(--cream);border-radius:8px;padding:8px 10px;">
          <p style="font-size:10px;color:var(--dingley);margin:0 0 2px;font-weight:600;">KATEGORI</p>
          <p style="font-size:13px;font-weight:700;color:var(--pine);margin:0;">{{ $product->category ?? '-' }}</p>
        </div>
        <div style="background:var(--cream);border-radius:8px;padding:8px 10px;">
          <p style="font-size:10px;color:var(--dingley);margin:0 0 2px;font-weight:600;">PICKUP DEADLINE</p>
          <p style="font-size:11px;font-weight:700;color:var(--pine);margin:0;">{{ $product->pickup_deadline->format('d M, H:i') }}</p>
        </div>
        <div style="background:var(--cream);border-radius:8px;padding:8px 10px;">
          <p style="font-size:10px;color:var(--dingley);margin:0 0 2px;font-weight:600;">STATUS</p>
          <p id="productStatus" style="font-size:12px;font-weight:700;color:{{ $stockAvailable ? 'var(--dingley)' : 'var(--copper)' }};margin:0;">
            {{ $stockAvailable ? '✅ Ready to Grab' : '❌ Stok Habis' }}
          </p>
        </div>
      </div>

      @if($product->description)
      <div style="border-top:1px solid #e8e4dc;padding-top:12px;margin-bottom:14px;">
        <p style="font-size:11px;font-weight:700;color:var(--dingley);margin:0 0 4px;letter-spacing:.5px;">DESKRIPSI</p>
        <p style="font-size:12px;color:#666;margin:0;line-height:1.6;">{{ $product->description }}</p>
      </div>
      @endif

      {{-- Add to Cart Form --}}
      <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        {{-- Qty --}}
        <div class="flex items-center gap-3 mb-4">
          <p style="font-size:12px;font-weight:600;color:var(--pine);margin:0;flex-shrink:0;">Jumlah:</p>
          <div class="flex items-center" style="background:var(--cream);border-radius:8px;overflow:hidden;border:1px solid #e8e4dc;">
            <button type="button" onclick="decrease()"
              style="width:36px;height:36px;background:transparent;border:none;color:var(--dingley);font-size:18px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;">−</button>
            <input id="qty" name="quantity" type="number" value="1" min="1" max="{{ $product->available_stock }}"
              style="width:40px;text-align:center;border:none;background:transparent;font-size:14px;font-weight:700;color:var(--pine);">
            <button type="button" onclick="increase()"
              style="width:36px;height:36px;background:transparent;border:none;color:var(--dingley);font-size:18px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;">+</button>
          </div>
          <p id="pricePreview" style="font-size:13px;font-weight:700;color:var(--dingley);margin:0;">
            = Rp {{ number_format($product->dynamic_price,0,',','.') }}
          </p>
        </div>

        <button type="button"
          onclick="handleAddToCart({{ $product->time_remaining_minutes }}, '{{ $product->pickup_deadline }}')"
          @if(!$stockAvailable) disabled @endif
          class="btn-cart"
          id="cartBtn">
          {{ !$stockAvailable ? 'Stok Habis' : '🛒 Tambah ke Keranjang' }}
        </button>
      </form>

    </div>
  </div>
</div>

{{-- MODAL EXPIRED --}}
<div id="expiredModal" class="fixed inset-0 hidden items-center justify-center z-50" style="background:rgba(0,0,0,.5);">
  <div style="background:#fff;border-radius:16px;padding:24px;max-width:340px;width:90%;text-align:center;">
    <div style="width:48px;height:48px;background:rgba(196,134,109,.15);border-radius:12px;margin:0 auto 12px;display:flex;align-items:center;justify-content:center;">
      <svg class="w-6 h-6" style="color:var(--copper)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    </div>
    <h2 style="font-size:16px;font-weight:700;color:var(--pine);margin:0 0 8px;">Produk Sudah Expired</h2>
    <p id="expiredText" style="font-size:12px;color:#666;margin:0 0 16px;"></p>
    <div class="flex gap-2">
      <button onclick="closeModal()" style="flex:1;border:1px solid #e8e4dc;border-radius:8px;padding:10px;font-size:13px;font-weight:600;color:var(--dingley);background:#fff;cursor:pointer;">Batal</button>
      <button onclick="confirmExpired()" style="flex:1;background:var(--dingley);border:none;border-radius:8px;padding:10px;font-size:13px;font-weight:600;color:#fff;cursor:pointer;">Tetap Beli</button>
    </div>
  </div>
</div>

<div class="md:hidden h-16"></div>

{{-- BOTTOM NAV --}}
<div class="md:hidden fixed bottom-0 left-0 right-0 z-50" style="background:var(--pine);border-top:1px solid var(--kombu);padding:8px 0;">
  <div class="grid grid-cols-3">
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

<script>
const dynamicPrice = {{ $product->dynamic_price }};
const maxStock = {{ $product->available_stock }};

function increase() {
  let qty = document.getElementById('qty');
  if (parseInt(qty.value) < maxStock) {
    qty.value = parseInt(qty.value) + 1;
    updatePreview();
  }
}
function decrease() {
  let qty = document.getElementById('qty');
  if (parseInt(qty.value) > 1) {
    qty.value = parseInt(qty.value) - 1;
    updatePreview();
  }
}
function updatePreview() {
  let qty = parseInt(document.getElementById('qty').value);
  let total = qty * dynamicPrice;
  document.getElementById('pricePreview').innerText =
    '= Rp ' + total.toLocaleString('id-ID');
}

function handleAddToCart(timeRemaining, deadline) {
  if (timeRemaining <= 0) {
    document.getElementById('expiredText').innerHTML =
      'Produk ini sudah expired pada:<br><b>' + deadline + '</b><br><br>Apakah kamu tetap ingin membeli?';
    document.getElementById('expiredModal').classList.remove('hidden');
    document.getElementById('expiredModal').classList.add('flex');
  } else {
    document.getElementById('addToCartForm').submit();
  }
}
function confirmExpired() { document.getElementById('addToCartForm').submit(); }
function closeModal() {
  document.getElementById('expiredModal').classList.add('hidden');
  document.getElementById('expiredModal').classList.remove('flex');
}

// COUNTDOWN
const deadlineStr = "{{ $deadlineISO }}";
const countdownElem = document.getElementById('countdown');
const statusBadge = document.getElementById('productStatus');
if (deadlineStr) {
  const deadline = new Date(deadlineStr).getTime();
  const timer = setInterval(() => {
    const now = new Date().getTime();
    const dist = deadline - now;
    if (dist <= 0) {
      countdownElem.innerText = 'Expired';
      clearInterval(timer);
      return;
    }
    const h = Math.floor(dist/(1000*60*60));
    const m = Math.floor((dist%(1000*60*60))/(1000*60));
    const s = Math.floor((dist%(1000*60))/1000);
    countdownElem.innerText = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
  }, 1000);
}
</script>

</body>
</html>
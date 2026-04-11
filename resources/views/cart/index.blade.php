<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Keranjang — App.Surplus</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
* { font-family: 'Plus Jakarta Sans', sans-serif; }
:root { --pine:#202808; --kombu:#33432B; --dingley:#6A784D; --brandy:#DEC59E; --copper:#C4866D; --cream:#f5f2ec; }
body { background: var(--cream); }
.btn-primary { background:var(--dingley);color:#fff;border-radius:10px;font-weight:700;padding:12px;width:100%;border:none;cursor:pointer;transition:opacity .2s;font-size:14px; }
.btn-primary:hover { opacity:.88; }
.btn-primary:disabled { background:#ccc;cursor:not-allowed; }
.card { background:#fff;border-radius:14px;border:1px solid #e8e4dc; }
.qty-btn { width:32px;height:32px;border:none;background:var(--cream);border-radius:7px;font-size:16px;font-weight:700;color:var(--dingley);cursor:pointer;display:flex;align-items:center;justify-content:center; }
.qty-btn:hover { background:#e8e4dc; }
</style>
</head>
<body>

{{-- NAVBAR --}}
<nav style="background:var(--pine);padding:12px 16px;" class="sticky top-0 z-50">
  <div class="max-w-5xl mx-auto flex items-center justify-between">
    <a href="/" style="display:flex;align-items:center;gap:8px;">
      <div style="width:28px;height:28px;background:var(--dingley);border-radius:7px;display:flex;align-items:center;justify-content:center;">
        <span style="color:var(--brandy);font-size:12px;font-weight:700;">S</span>
      </div>
      <span style="color:var(--brandy);font-size:14px;font-weight:700;">App.Surplus</span>
    </a>
    <h1 style="color:var(--brandy);font-size:14px;font-weight:700;">Keranjang Saya</h1>
    <div style="width:80px;"></div>
  </div>
</nav>

@php
  $total = 0;
  $hasOutOfStock = $cart->contains(fn($item) => $item->product->available_stock <= 0);
@endphp

<div class="max-w-5xl mx-auto px-4 py-5">

  @if(session('success'))
  <div style="background:rgba(106,120,77,.1);border:1px solid var(--dingley);border-radius:10px;padding:10px 14px;margin-bottom:16px;color:var(--kombu);font-size:13px;">
    ✅ {{ session('success') }}
  </div>
  @endif
  @if(session('error'))
  <div style="background:rgba(196,134,109,.1);border:1px solid var(--copper);border-radius:10px;padding:10px 14px;margin-bottom:16px;color:var(--copper);font-size:13px;">
    ❌ {{ session('error') }}
  </div>
  @endif

  @if(isset($merchant))
  <div style="background:var(--kombu);border-radius:10px;padding:10px 14px;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
    <div style="width:32px;height:32px;background:var(--dingley);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <span style="color:var(--brandy);font-size:13px;font-weight:700;">{{ substr($merchant->name,0,1) }}</span>
    </div>
    <div>
      <p style="color:var(--brandy);font-size:12px;font-weight:700;margin:0;">{{ $merchant->name }}</p>
      <p style="color:var(--dingley);font-size:10px;margin:0;">📍 {{ $merchant->address }}</p>
    </div>
  </div>
  @endif

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

    {{-- CART ITEMS --}}
    <div class="md:col-span-2 space-y-3">
      @forelse($cart as $item)
      @php
        $subtotal = $item->product->dynamic_price * $item->quantity;
        $total += $subtotal;
        $availableStock = $item->product->available_stock;
        $isExpired = $item->product->pickup_deadline < now();
      @endphp

      <div class="card p-4 flex gap-3 items-start {{ $availableStock <= 0 ? 'opacity-60' : '' }}">

        {{-- Checkbox --}}
        <input type="checkbox" class="checkout-checkbox mt-1 flex-shrink-0"
               data-merchant="{{ $item->product->store_id }}"
               value="{{ $item->id }}"
               style="width:16px;height:16px;accent-color:var(--dingley);">

        {{-- Image --}}
        <div style="width:72px;height:72px;border-radius:10px;overflow:hidden;background:#f0ede6;flex-shrink:0;">
          @if($item->product->image_url)
            <img src="{{ asset('storage/'.$item->product->image_url) }}" class="w-full h-full object-cover">
          @else
            <div class="w-full h-full flex items-center justify-center">
              <svg class="w-6 h-6" style="color:var(--brandy)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
          @endif
        </div>

        {{-- Info --}}
        <div class="flex-1 min-w-0">
          <div class="flex items-start justify-between gap-2">
            <div>
              <p style="font-size:13px;font-weight:700;color:var(--pine);margin:0 0 2px;">{{ $item->product->name }}</p>
              <p style="font-size:11px;color:var(--dingley);margin:0 0 4px;">{{ $item->product->store->name ?? '-' }}</p>
              <div class="flex items-center gap-2 mb-2">
                <span style="font-size:10px;font-weight:600;padding:2px 8px;border-radius:20px;{{ $isExpired ? 'background:rgba(196,134,109,.15);color:var(--copper)' : 'background:rgba(106,120,77,.12);color:var(--dingley)' }}">
                  {{ $isExpired ? '⚠️ Expired' : '✅ Ready to Grab' }}
                </span>
                <span style="font-size:10px;color:{{ $availableStock <= 0 ? 'var(--copper)' : 'var(--dingley)' }};font-weight:600;">
                  {{ $availableStock }} tersedia
                </span>
              </div>
            </div>
            {{-- Delete --}}
            <form action="{{ route('cart.delete', $item->id) }}" method="POST">
              @csrf
              @method('DELETE')
              <button style="background:rgba(196,134,109,.1);border:none;border-radius:7px;padding:5px 8px;cursor:pointer;color:var(--copper);font-size:11px;font-weight:600;">Hapus</button>
            </form>
          </div>

          {{-- Price & Qty --}}
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <button type="button" class="qty-btn" onclick="decrease({{ $item->id }}, {{ $item->product->dynamic_price }})" {{ $availableStock <= 0 ? 'disabled' : '' }}>−</button>
              <input id="qty-{{ $item->id }}" type="number" value="{{ $item->quantity }}"
                     style="width:36px;text-align:center;border:1px solid #e8e4dc;border-radius:7px;padding:4px;font-size:13px;font-weight:700;color:var(--pine);"
                     onchange="updateSubtotal({{ $item->id }}, {{ $item->product->dynamic_price }})"
                     {{ $availableStock <= 0 ? 'disabled' : '' }}>
              <button type="button" class="qty-btn" onclick="increase({{ $item->id }}, {{ $item->product->dynamic_price }})" {{ $availableStock <= 0 ? 'disabled' : '' }}>+</button>
            </div>
            <div style="text-align:right;">
              <p style="font-size:10px;color:#aaa;text-decoration:line-through;margin:0;">Rp {{ number_format($item->product->original_price,0,',','.') }}</p>
              <p id="subtotal-{{ $item->id }}" style="font-size:13px;font-weight:700;color:var(--dingley);margin:0;">Rp {{ number_format($subtotal,0,',','.') }}</p>
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="card p-10 text-center">
        <p style="font-size:32px;margin-bottom:8px;">🛒</p>
        <p style="font-weight:700;color:var(--pine);margin:0 0 4px;">Keranjang masih kosong</p>
        <p style="font-size:12px;color:var(--dingley);margin:0 0 16px;">Selamatkan produk favoritmu sekarang!</p>
        <a href="/" style="display:inline-block;background:var(--dingley);color:#fff;border-radius:10px;padding:10px 24px;font-size:13px;font-weight:700;">Cari Produk</a>
      </div>
      @endforelse
    </div>

    {{-- SUMMARY --}}
    <div class="space-y-3">
      <div class="card p-4">
        <p style="font-size:14px;font-weight:700;color:var(--pine);margin:0 0 14px;">Ringkasan Pesanan</p>

        <div style="border-top:1px solid #e8e4dc;padding-top:12px;margin-bottom:12px;">
          <div class="flex justify-between items-center mb-2">
            <span style="font-size:12px;color:var(--dingley);">Subtotal</span>
            <span id="total" style="font-size:14px;font-weight:700;color:var(--pine);">Rp {{ number_format($total,0,',','.') }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span style="font-size:11px;color:#aaa;">Item dipilih</span>
            <span id="selectedCount" style="font-size:11px;color:var(--dingley);font-weight:600;">0 item</span>
          </div>
        </div>

        <form action="{{ route('checkout.index') }}" method="GET" id="checkoutForm">
          @csrf
          <input type="hidden" name="product_ids" id="product_ids">
          <button type="button" id="checkoutBtn"
            {{ $hasOutOfStock ? 'disabled' : '' }}
            class="btn-primary">
            {{ $hasOutOfStock ? 'Ada stok tidak tersedia' : 'Lanjut ke Checkout →' }}
          </button>
        </form>
      </div>

      {{-- Info --}}
      <div class="card p-4">
        <p style="font-size:11px;font-weight:700;color:var(--dingley);margin:0 0 8px;letter-spacing:.5px;">INFO PENTING</p>
        <div class="space-y-2">
          <p style="font-size:11px;color:#666;margin:0;">✅ Pilih produk dari 1 merchant untuk checkout</p>
          <p style="font-size:11px;color:#666;margin:0;">⏱ Stok dikunci saat masuk keranjang</p>
          <p style="font-size:11px;color:#666;margin:0;">⚠️ 3x tidak pickup = akun dibatasi</p>
        </div>
      </div>
    </div>

  </div>
</div>

{{-- MODAL --}}
<div id="checkoutModal" class="fixed inset-0 hidden items-center justify-center z-50" style="background:rgba(0,0,0,.5);">
  <div style="background:#fff;border-radius:16px;padding:24px;max-width:320px;width:90%;text-align:center;">
    <p style="font-size:24px;margin-bottom:8px;">🛒</p>
    <h3 style="font-size:15px;font-weight:700;color:var(--pine);margin:0 0 8px;">Pilih produk dulu!</h3>
    <p style="font-size:12px;color:#666;margin:0 0 16px;">Centang minimal 1 produk untuk melanjutkan checkout.</p>
    <button id="closeModal" style="background:var(--dingley);color:#fff;border:none;border-radius:10px;padding:10px 24px;font-size:13px;font-weight:700;cursor:pointer;">Oke</button>
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
    <a href="/my-orders" class="flex flex-col items-center gap-1 py-1">
      <svg class="w-5 h-5" style="color:var(--dingley)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      <span style="font-size:9px;color:var(--dingley);">Pesanan</span>
    </a>
  </div>
</div>

<script>
function increase(id, price) {
  let qty = document.getElementById('qty-' + id);
  qty.value = parseInt(qty.value) + 1;
  updateSubtotal(id, price);
}
function decrease(id, price) {
  let qty = document.getElementById('qty-' + id);
  if (parseInt(qty.value) > 1) { qty.value = parseInt(qty.value) - 1; updateSubtotal(id, price); }
}
function updateSubtotal(id, price) {
  let qty = document.getElementById('qty-' + id).value;
  document.getElementById('subtotal-' + id).innerText = 'Rp ' + (qty * price).toLocaleString('id-ID');
  updateTotal();
}
function updateTotal() {
  const checked = document.querySelectorAll('.checkout-checkbox:checked');
  let grand = 0;
  checked.forEach(cb => {
    const id = cb.value;
    const el = document.getElementById('subtotal-' + id);
    if (el) grand += parseInt(el.innerText.replace(/[^\d]/g, ''));
  });
  document.getElementById('total').innerText = 'Rp ' + grand.toLocaleString('id-ID');
  document.getElementById('selectedCount').innerText = checked.length + ' item';
}

const checkboxes = document.querySelectorAll('.checkout-checkbox');
let selectedMerchant = null;
checkboxes.forEach(cb => {
  cb.addEventListener('change', function() {
    const mid = this.dataset.merchant;
    if (this.checked) {
      if (!selectedMerchant) selectedMerchant = mid;
      else if (selectedMerchant !== mid) {
        alert('Hanya bisa checkout dari 1 merchant dalam 1 pesanan!');
        this.checked = false; return;
      }
    } else {
      if (!Array.from(checkboxes).some(c => c.checked)) selectedMerchant = null;
    }
    updateTotal();
  });
});

document.getElementById('checkoutBtn').addEventListener('click', function() {
  const ids = Array.from(document.querySelectorAll('.checkout-checkbox:checked')).map(c => c.value);
  if (ids.length === 0) {
    document.getElementById('checkoutModal').classList.remove('hidden');
    document.getElementById('checkoutModal').classList.add('flex');
    return;
  }
  document.getElementById('product_ids').value = ids.join(',');
  document.getElementById('checkoutForm').submit();
});
document.getElementById('closeModal').addEventListener('click', function() {
  document.getElementById('checkoutModal').classList.add('hidden');
  document.getElementById('checkoutModal').classList.remove('flex');
});
</script>
</body>
</html>
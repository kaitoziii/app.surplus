<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout — App.Surplus</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
* { font-family: 'Plus Jakarta Sans', sans-serif; }
:root { --pine:#202808; --kombu:#33432B; --dingley:#6A784D; --brandy:#DEC59E; --copper:#C4866D; --cream:#f5f2ec; }
body { background: var(--cream); }
.card { background:#fff; border-radius:14px; border:1px solid #e8e4dc; padding:20px; }
.radio-label input[type=radio] { display:none; }
.radio-label div { border:1.5px solid #e8e4dc; border-radius:10px; padding:12px 16px; cursor:pointer; transition:all .2s; }
.radio-label input[type=radio]:checked + div { border-color:var(--dingley); background:rgba(106,120,77,.06); }
.btn-primary { background:var(--dingley);color:#fff;border-radius:10px;font-weight:700;padding:14px;width:100%;border:none;cursor:pointer;font-size:14px;transition:opacity .2s; }
.btn-primary:hover { opacity:.88; }
</style>
</head>
<body>

{{-- NAVBAR --}}
<nav style="background:var(--pine);padding:12px 16px;" class="sticky top-0 z-50">
  <div class="max-w-4xl mx-auto flex items-center gap-3">
    <a href="/cart" style="color:var(--dingley);display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      Keranjang
    </a>
    <span style="color:var(--dingley);">|</span>
    <span style="color:var(--brandy);font-size:14px;font-weight:700;">Atur Pesanan</span>
  </div>
</nav>

<form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
@csrf

<div class="max-w-4xl mx-auto px-4 py-5 space-y-4">

  {{-- STORE INFO --}}
  <div class="card">
    <p style="font-size:11px;font-weight:700;color:var(--dingley);letter-spacing:.5px;margin:0 0 10px;">MERCHANT</p>
    <div class="flex items-center gap-3">
      <div style="width:40px;height:40px;background:linear-gradient(135deg,var(--kombu),var(--dingley));border-radius:10px;flex-shrink:0;" class="flex items-center justify-center">
        <span style="color:var(--brandy);font-size:16px;font-weight:700;">{{ substr($store->name ?? 'S', 0, 1) }}</span>
      </div>
      <div>
        <p style="font-size:14px;font-weight:700;color:var(--pine);margin:0;">{{ $store->name ?? '-' }}</p>
        <p style="font-size:11px;color:var(--dingley);margin:0;">📍 {{ $store->address ?? 'Alamat belum diisi' }}</p>
      </div>
    </div>
  </div>

  {{-- METODE PENGAMBILAN --}}
  <div class="card">
    <p style="font-size:11px;font-weight:700;color:var(--dingley);letter-spacing:.5px;margin:0 0 12px;">METODE PENGAMBILAN</p>
    <div class="grid grid-cols-2 gap-3">
      <label class="radio-label">
        <input type="radio" name="pickup_method" value="take_away" checked>
        <div>
          <p style="font-size:13px;font-weight:700;color:var(--pine);margin:0 0 2px;">🏃 Take Away</p>
          <p style="font-size:11px;color:var(--dingley);margin:0;">Ambil sendiri di toko</p>
        </div>
      </label>
      <label class="radio-label">
        <input type="radio" name="pickup_method" value="delivery">
        <div>
          <p style="font-size:13px;font-weight:700;color:var(--pine);margin:0 0 2px;">🛵 Delivery</p>
          <p style="font-size:11px;color:var(--dingley);margin:0;">Diantar ke alamatmu</p>
        </div>
      </label>
    </div>
  </div>

  {{-- PESANAN --}}
  <div class="card">
    <p style="font-size:11px;font-weight:700;color:var(--dingley);letter-spacing:.5px;margin:0 0 12px;">PESANAN</p>
    <div class="space-y-3">
      @foreach($cartItems as $item)
      <div style="display:flex;align-items:center;gap:12px;padding-bottom:12px;border-bottom:1px solid #f0ede6;">
        <div style="width:56px;height:56px;border-radius:10px;overflow:hidden;background:#f0ede6;flex-shrink:0;">
          @if($item->product->image_url)
            <img src="{{ asset('storage/'.$item->product->image_url) }}" class="w-full h-full object-cover">
          @else
            <div class="w-full h-full flex items-center justify-center">
              <svg class="w-5 h-5" style="color:var(--brandy)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
          @endif
        </div>
        <div class="flex-1 min-w-0">
          <p style="font-size:13px;font-weight:700;color:var(--pine);margin:0 0 2px;">{{ $item->product->name }}</p>
          <p style="font-size:11px;color:var(--dingley);margin:0;">Qty: {{ $item->quantity }}</p>
        </div>
        <div style="text-align:right;flex-shrink:0;">
          @if($item->product->discount_percentage > 0)
          <p style="font-size:10px;color:#aaa;text-decoration:line-through;margin:0;">Rp {{ number_format($item->product->original_price,0,',','.') }}</p>
          @endif
          <p style="font-size:13px;font-weight:700;color:var(--dingley);margin:0;">Rp {{ number_format($item->product->dynamic_price * $item->quantity,0,',','.') }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  {{-- PEMBAYARAN --}}
  <div class="card">
    <p style="font-size:11px;font-weight:700;color:var(--dingley);letter-spacing:.5px;margin:0 0 12px;">RINCIAN PEMBAYARAN</p>
    <div class="space-y-2 mb-4">
      <div class="flex justify-between">
        <span style="font-size:13px;color:#666;">Subtotal</span>
        <span style="font-size:13px;color:var(--pine);">Rp {{ number_format($subtotal,0,',','.') }}</span>
      </div>
      <div class="flex justify-between">
        <span style="font-size:13px;color:#666;">Biaya Penanganan</span>
        <span style="font-size:13px;color:var(--pine);">Rp {{ number_format($handlingFee,0,',','.') }}</span>
      </div>
      <div style="border-top:1px solid #e8e4dc;padding-top:8px;" class="flex justify-between">
        <span style="font-size:14px;font-weight:700;color:var(--pine);">Total</span>
        <span style="font-size:16px;font-weight:700;color:var(--dingley);">Rp {{ number_format($totalPayment,0,',','.') }}</span>
      </div>
    </div>

    <p style="font-size:11px;font-weight:700;color:var(--dingley);letter-spacing:.5px;margin:0 0 10px;">METODE PEMBAYARAN</p>
    <div class="grid grid-cols-2 gap-3 mb-3">
      <label class="radio-label">
        <input type="radio" name="payment_method" value="cash" checked>
        <div style="text-align:center;">
          <p style="font-size:13px;font-weight:700;color:var(--pine);margin:0 0 2px;">💵 Cash</p>
          <p style="font-size:11px;color:var(--dingley);margin:0;">Bayar di tempat</p>
        </div>
      </label>
      <label class="radio-label">
        <input type="radio" name="payment_method" value="transfer">
        <div style="text-align:center;">
          <p style="font-size:13px;font-weight:700;color:var(--pine);margin:0 0 2px;">🏦 Transfer</p>
          <p style="font-size:11px;color:var(--dingley);margin:0;">Upload bukti bayar</p>
        </div>
      </label>
    </div>

    <div id="transferUpload" class="hidden">
      <label style="font-size:12px;font-weight:600;color:var(--pine);display:block;margin-bottom:6px;">Upload Bukti Transfer</label>
      <input type="file" name="transfer_proof" id="transferProof" accept="image/*"
             style="width:100%;border:1.5px dashed var(--dingley);border-radius:10px;padding:10px;font-size:12px;background:rgba(106,120,77,.04);">
      <p id="transferError" class="hidden" style="color:var(--copper);font-size:11px;margin-top:4px;">Harap upload bukti transfer!</p>
    </div>
  </div>

  {{-- CATATAN --}}
  <div class="card">
    <p style="font-size:11px;font-weight:700;color:var(--dingley);letter-spacing:.5px;margin:0 0 8px;">CATATAN (Opsional)</p>
    <textarea name="notes" rows="2" placeholder="Contoh: Tolong pisahkan sausnya..."
      style="width:100%;border:1.5px solid #e8e4dc;border-radius:10px;padding:10px 12px;font-size:12px;resize:none;background:var(--cream);color:var(--pine);"
      onfocus="this.style.borderColor='var(--dingley)'" onblur="this.style.borderColor='#e8e4dc'"></textarea>
  </div>

  <button type="submit" class="btn-primary">🛒 Buat Pesanan</button>

  <p style="font-size:11px;color:var(--dingley);text-align:center;margin-top:8px;">
    ⚠️ 3x tidak pickup = akun dibatasi sementara
  </p>

</div>
</form>

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
document.querySelectorAll('input[name="payment_method"]').forEach(r => {
  r.addEventListener('change', function() {
    document.getElementById('transferUpload').classList.toggle('hidden', this.value !== 'transfer');
  });
});
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
  const method = document.querySelector('input[name="payment_method"]:checked')?.value;
  if (method === 'transfer' && !document.getElementById('transferProof').value) {
    e.preventDefault();
    document.getElementById('transferError').classList.remove('hidden');
  }
});
</script>
</body>
</html>
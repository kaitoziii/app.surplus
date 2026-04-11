<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Riwayat Pesanan – App.Surplus</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
* { font-family: 'Plus Jakarta Sans', sans-serif; }
:root { --pine:#202808; --kombu:#33432B; --dingley:#6A784D; --brandy:#DEC59E; --copper:#C4866D; --cream:#f5f2ec; }
body { background: var(--cream); }
.card { background:#fff; border-radius:14px; border:1px solid #e8e4dc; }
</style>
</head>
<body>

<nav style="background:var(--pine);padding:12px 16px;" class="sticky top-0 z-50">
  <div class="max-w-3xl mx-auto flex items-center justify-between">
    <a href="/" style="display:flex;align-items:center;gap:8px;">
      <div style="width:28px;height:28px;background:var(--dingley);border-radius:7px;display:flex;align-items:center;justify-content:center;">
        <span style="color:var(--brandy);font-size:12px;font-weight:700;">S</span>
      </div>
      <span style="color:var(--brandy);font-size:14px;font-weight:700;">App.Surplus</span>
    </a>
    <h1 style="color:var(--brandy);font-size:14px;font-weight:700;">Riwayat Pesanan</h1>
    <div style="width:80px;"></div>
  </div>
</nav>

<div class="max-w-3xl mx-auto px-4 py-5">

  @if($orders->count() > 0)

  {{-- Summary Stats --}}
  <div class="grid grid-cols-3 gap-3 mb-5">
    <div style="background:#fff;border-radius:12px;border:1px solid #e8e4dc;padding:14px;text-align:center;">
      <p style="font-size:22px;font-weight:700;color:var(--pine);margin:0;">{{ $orders->count() }}</p>
      <p style="font-size:11px;color:var(--dingley);margin:0;">Total Pesanan</p>
    </div>
    <div style="background:#fff;border-radius:12px;border:1px solid #e8e4dc;padding:14px;text-align:center;">
      <p style="font-size:16px;font-weight:700;color:var(--dingley);margin:0;">Rp {{ number_format($orders->sum('price_paid')/1000, 0, ',', '.')}}k</p>
      <p style="font-size:11px;color:var(--dingley);margin:0;">Total Belanja</p>
    </div>
    <div style="background:#fff;border-radius:12px;border:1px solid #e8e4dc;padding:14px;text-align:center;">
      <p style="font-size:16px;font-weight:700;color:var(--copper);margin:0;">Rp {{ number_format($orders->sum('savings_amount')/1000, 0, ',', '.') }}k</p>
      <p style="font-size:11px;color:var(--dingley);margin:0;">Total Hemat</p>
    </div>
  </div>

  <div class="space-y-3">
    @foreach($orders as $order)
    <div class="card p-4">
      <div class="flex items-start justify-between mb-3">
        <div>
          <p style="font-size:12px;color:var(--dingley);font-weight:600;margin:0 0 2px;">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
          <p style="font-size:11px;color:#aaa;margin:0;">{{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <span style="background:rgba(106,120,77,.12);color:var(--dingley);font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;">
          ✅ Selesai
        </span>
      </div>

      <div style="display:flex;align-items:center;gap:10px;padding:10px;background:var(--cream);border-radius:10px;margin-bottom:10px;">
        <div style="width:52px;height:52px;border-radius:8px;overflow:hidden;background:#e8e4dc;flex-shrink:0;">
          @if($order->product && $order->product->image_url)
            <img src="{{ asset('storage/'.$order->product->image_url) }}" class="w-full h-full object-cover">
          @else
            <div class="w-full h-full flex items-center justify-center">
              <svg class="w-5 h-5" style="color:var(--brandy)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
          @endif
        </div>
        <div class="flex-1 min-w-0">
          <p style="font-size:13px;font-weight:700;color:var(--pine);margin:0 0 2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            {{ $order->product->name ?? 'Produk tidak tersedia' }}
          </p>
          <p style="font-size:11px;color:var(--dingley);margin:0;">
            Qty: {{ $order->quantity }} · {{ $order->product->store->name ?? '-' }}
          </p>
        </div>
        <div style="text-align:right;flex-shrink:0;">
          <p style="font-size:14px;font-weight:700;color:var(--dingley);margin:0;">Rp {{ number_format($order->price_paid, 0, ',', '.') }}</p>
          @if($order->savings_amount > 0)
          <p style="font-size:10px;color:var(--copper);margin:0;">Hemat Rp {{ number_format($order->savings_amount, 0, ',', '.') }}</p>
          @endif
        </div>
      </div>

      <div style="background:rgba(106,120,77,.08);border-radius:10px;padding:8px 12px;display:flex;align-items:center;gap:8px;">
        <span style="font-size:16px;">🌿</span>
        <p style="font-size:11px;color:var(--dingley);margin:0;font-weight:600;">Kamu telah menyelamatkan makanan ini dari menjadi limbah!</p>
      </div>
    </div>
    @endforeach
  </div>

  @else
  <div class="card p-10 text-center">
    <p style="font-size:40px;margin-bottom:8px;">📦</p>
    <p style="font-weight:700;color:var(--pine);margin:0 0 4px;">Belum ada riwayat pesanan</p>
    <p style="font-size:12px;color:var(--dingley);margin:0 0 16px;">Pesanan yang sudah selesai akan muncul di sini</p>
    <a href="/" style="display:inline-block;background:var(--dingley);color:#fff;border-radius:10px;padding:10px 24px;font-size:13px;font-weight:700;">Mulai Belanja</a>
  </div>
  @endif

</div>

<div class="md:hidden h-16"></div>

<div class="md:hidden fixed bottom-0 left-0 right-0 z-50" style="background:var(--pine);border-top:1px solid var(--kombu);padding:8px 0;">
  <div class="grid grid-cols-3">
    <a href="/" class="flex flex-col items-center gap-1 py-1">
      <svg class="w-5 h-5" style="color:var(--dingley)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
      <span style="font-size:9px;color:var(--dingley);">Beranda</span>
    </a>
    <a href="/cart" class="flex flex-col items-center gap-1 py-1">
      <svg class="w-5 h-5" style="color:var(--dingley)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      <span style="font-size:9px;color:var(--dingley);">Keranjang</span>
    </a>
    <a href="/my-orders" class="flex flex-col items-center gap-1 py-1">
      <svg class="w-5 h-5" style="color:var(--brandy)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      <span style="font-size:9px;color:var(--brandy);font-weight:600;">Pesanan</span>
    </a>
  </div>
</div>

</body>
</html>
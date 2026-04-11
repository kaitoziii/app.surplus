<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesanan Saya – App.Surplus</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
* { font-family: 'Plus Jakarta Sans', sans-serif; }
:root { --pine:#202808; --kombu:#33432B; --dingley:#6A784D; --brandy:#DEC59E; --copper:#C4866D; --cream:#f5f2ec; }
body { background: var(--cream); }
.card { background:#fff; border-radius:14px; border:1px solid #e8e4dc; }
.tab-btn { padding:8px 16px; border-radius:20px; font-size:12px; font-weight:600; border:none; cursor:pointer; transition:all .2s; background:transparent; color:var(--dingley); }
.tab-btn.active { background:var(--dingley); color:#fff; }
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
    <h1 style="color:var(--brandy);font-size:14px;font-weight:700;">Pesanan Saya</h1>
    <div style="width:80px;"></div>
  </div>
</nav>

<div class="max-w-3xl mx-auto px-4 py-5">

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

  {{-- TABS --}}
  <div style="background:#fff;border-radius:12px;border:1px solid #e8e4dc;padding:6px;display:flex;gap:4px;margin-bottom:16px;overflow-x:auto;">
    <button class="tab-btn active" onclick="filterOrders('all', this)">Semua</button>
    <button class="tab-btn" onclick="filterOrders('pending', this)">Menunggu</button>
    <button class="tab-btn" onclick="filterOrders('confirmed', this)">Dikonfirmasi</button>
    <button class="tab-btn" onclick="filterOrders('picked_up', this)">Selesai</button>
    <button class="tab-btn" onclick="filterOrders('cancelled', this)">Dibatalkan</button>
  </div>

  {{-- ORDER LIST --}}
  <div class="space-y-3" id="orderList">
    @forelse($orders as $order)
    @php
      $status = $order->status ?? 'pending';
      $label  = \App\Models\Transaction::STATUS_LABELS[$status] ?? ucfirst($status);
      $color  = \App\Models\Transaction::STATUS_COLORS[$status] ?? ['bg'=>'#eee','text'=>'#666'];
    @endphp

    <div class="card p-4 order-item" data-status="{{ $status }}">

      {{-- Header --}}
      <div class="flex items-start justify-between mb-3">
        <div>
          <p style="font-size:12px;color:var(--dingley);font-weight:600;margin:0 0 2px;">
            #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
          </p>
          <p style="font-size:11px;color:#aaa;margin:0;">
            {{ $order->created_at->format('d M Y, H:i') }}
          </p>
        </div>
        <span style="background:{{ $color['bg'] }};color:{{ $color['text'] }};font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;">
          {{ $label }}
        </span>
      </div>

      {{-- Product --}}
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
          <p style="font-size:14px;font-weight:700;color:var(--dingley);margin:0;">
            Rp {{ number_format($order->price_paid, 0, ',', '.') }}
          </p>
          @if($order->savings_amount > 0)
          <p style="font-size:10px;color:var(--copper);margin:0;">
            Hemat Rp {{ number_format($order->savings_amount, 0, ',', '.') }}
          </p>
          @endif
        </div>
      </div>

      {{-- Pickup Code (confirmed / picked_up) --}}
      @if($order->pickup_code && in_array($status, ['confirmed', 'picked_up']))
      <div style="background:var(--kombu);border-radius:10px;padding:10px 14px;display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
        <div>
          <p style="color:var(--dingley);font-size:10px;font-weight:600;margin:0;">KODE PICKUP</p>
          <p style="color:var(--brandy);font-size:22px;font-weight:700;letter-spacing:5px;margin:0;">{{ $order->pickup_code }}</p>
        </div>
        <svg class="w-8 h-8" style="color:var(--dingley)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
        </svg>
      </div>
      @endif

      {{-- Status Timeline --}}
      @if($status !== 'cancelled')
      @php
        $steps     = ['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'picked_up' => 'Selesai'];
        $stepKeys  = array_keys($steps);
        $currentIdx = array_search($status, $stepKeys);
        if ($currentIdx === false) $currentIdx = 0;
      @endphp
      <div style="display:flex;align-items:center;font-size:10px;">
        @foreach($steps as $key => $label)
        @php $idx = array_search($key, $stepKeys); $done = $idx <= $currentIdx; @endphp
        <div style="display:flex;align-items:center;flex:1;">
          <div style="display:flex;flex-direction:column;align-items:center;gap:3px;">
            <div style="width:22px;height:22px;border-radius:50%;background:{{ $done ? 'var(--dingley)' : '#e8e4dc' }};display:flex;align-items:center;justify-content:center;transition:all .3s;">
              @if($done)
              <svg style="width:12px;height:12px;" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
              @endif
            </div>
            <p style="color:{{ $done ? 'var(--dingley)' : '#bbb' }};font-weight:{{ $done ? '600' : '400' }};margin:0;white-space:nowrap;">{{ $label }}</p>
          </div>
          @if(!$loop->last)
          <div style="flex:1;height:2px;background:{{ ($done && $idx < $currentIdx) ? 'var(--dingley)' : '#e8e4dc' }};margin:0 4px;margin-bottom:14px;"></div>
          @endif
        </div>
        @endforeach
      </div>
      @else
      <div style="background:rgba(196,134,109,.08);border-radius:8px;padding:8px 12px;">
        <p style="font-size:11px;color:var(--copper);margin:0;font-weight:600;">❌ Pesanan dibatalkan</p>
      </div>
      @endif

    </div>
    @empty
    <div class="card p-10 text-center">
      <p style="font-size:40px;margin-bottom:8px;">📦</p>
      <p style="font-weight:700;color:var(--pine);margin:0 0 4px;">Belum ada pesanan</p>
      <p style="font-size:12px;color:var(--dingley);margin:0 0 16px;">Yuk selamatkan makanan surplus pertamamu!</p>
      <a href="/" style="display:inline-block;background:var(--dingley);color:#fff;border-radius:10px;padding:10px 24px;font-size:13px;font-weight:700;">Cari Produk</a>
    </div>
    @endforelse
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
      <svg class="w-5 h-5" style="color:var(--dingley)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      <span style="font-size:9px;color:var(--dingley);">Keranjang</span>
    </a>
    <a href="/my-orders" class="flex flex-col items-center gap-1 py-1">
      <svg class="w-5 h-5" style="color:var(--brandy)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      <span style="font-size:9px;color:var(--brandy);font-weight:600;">Pesanan</span>
    </a>
  </div>
</div>

<script>
function filterOrders(status, btn) {
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.order-item').forEach(item => {
    item.style.display = (status === 'all' || item.dataset.status === status) ? 'block' : 'none';
  });
}
</script>
</body>
</html>
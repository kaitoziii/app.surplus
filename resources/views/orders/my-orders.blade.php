@extends('layouts.consumer')
@section('title', 'Pesanan Saya — App.Surplus')
@section('page-title', 'Pesanan Saya')

@section('content')

{{-- TABS --}}
<div style="background:#fff;border-radius:12px;border:1px solid #EDE8DF;padding:6px;display:flex;gap:4px;margin-bottom:20px;overflow-x:auto;scrollbar-width:none;">
  <button onclick="filterOrders('all',this)" id="tab-all"
    style="padding:8px 16px;border-radius:8px;font-size:12px;font-weight:600;border:none;cursor:pointer;white-space:nowrap;background:var(--pine);color:#fff;transition:.2s;">
    Semua
  </button>
  <button onclick="filterOrders('pending',this)"
    style="padding:8px 16px;border-radius:8px;font-size:12px;font-weight:600;border:none;cursor:pointer;white-space:nowrap;background:transparent;color:var(--dingley);transition:.2s;">
    Menunggu
  </button>
  <button onclick="filterOrders('confirmed',this)"
    style="padding:8px 16px;border-radius:8px;font-size:12px;font-weight:600;border:none;cursor:pointer;white-space:nowrap;background:transparent;color:var(--dingley);transition:.2s;">
    Dikonfirmasi
  </button>
  <button onclick="filterOrders('siap diambil',this)"
    style="padding:8px 16px;border-radius:8px;font-size:12px;font-weight:600;border:none;cursor:pointer;white-space:nowrap;background:transparent;color:var(--dingley);transition:.2s;">
    Siap Diambil
  </button>
  <button onclick="filterOrders('selesai',this)"
    style="padding:8px 16px;border-radius:8px;font-size:12px;font-weight:600;border:none;cursor:pointer;white-space:nowrap;background:transparent;color:var(--dingley);transition:.2s;">
    Selesai
  </button>
</div>

{{-- ORDER LIST --}}
<div id="orderList" style="display:flex;flex-direction:column;gap:14px;">
  @forelse($orders as $order)
  @php
    $status = $order->status ?? 'pending';
    $statusLabel = [
      'pending'      => '⏳ Menunggu',
      'confirmed'    => '✅ Dikonfirmasi',
      'siap diambil' => '🟢 Siap Diambil',
      'sedang dikirim'=> '🚚 Sedang Dikirim',
      'picked_up'    => '🎉 Selesai',
      'selesai'      => '🎉 Selesai',
      'cancelled'    => '❌ Dibatalkan',
    ][$status] ?? ucfirst($status);
    $statusColors = match($status) {
      'picked_up','selesai' => ['bg'=>'rgba(106,120,77,.12)','text'=>'var(--dingley)'],
      'confirmed','siap diambil' => ['bg'=>'rgba(51,67,43,.1)','text'=>'var(--kombu)'],
      'cancelled' => ['bg'=>'rgba(196,134,109,.12)','text'=>'var(--copper)'],
      default => ['bg'=>'rgba(222,197,158,.25)','text'=>'#8a6a2a'],
    };
  @endphp
  <div class="order-item" data-status="{{ $status }}"
    style="background:#fff;border-radius:14px;border:1px solid #EDE8DF;padding:18px;transition:.2s;"
    onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.08)'"
    onmouseout="this.style.boxShadow=''">

    {{-- Header --}}
    <div style="display:flex;align-items:start;justify-content:space-between;margin-bottom:14px;">
      <div>
        <p style="font-size:12px;color:var(--dingley);margin:0 0 2px;font-weight:600;">
          #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
        </p>
        <p style="font-size:11px;color:#aaa;margin:0;">
          {{ $order->created_at->format('d M Y, H:i') }}
        </p>
      </div>
      <span style="background:{{ $statusColors['bg'] }};color:{{ $statusColors['text'] }};font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px;">
        {{ $statusLabel }}
      </span>
    </div>

    {{-- Product --}}
    <div style="display:flex;align-items:center;gap:12px;padding:12px;background:#F7F4EE;border-radius:12px;margin-bottom:14px;">
      <div style="width:52px;height:52px;border-radius:10px;overflow:hidden;background:#e8e4dc;flex-shrink:0;">
        @if($order->product && $order->product->image_url)
          <img src="{{ asset('storage/'.$order->product->image_url) }}" style="width:100%;height:100%;object-fit:cover;">
        @else
          <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="var(--brandy)">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
          </div>
        @endif
      </div>
      <div style="flex:1;min-width:0;">
        <p style="font-size:13px;font-weight:700;color:var(--pine);margin:0 0 3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
          {{ $order->product->name ?? 'Produk tidak tersedia' }}
        </p>
        <p style="font-size:11px;color:var(--dingley);margin:0;">
          Qty: {{ $order->quantity ?? 1 }} · {{ $order->product->store->name ?? '-' }}
        </p>
      </div>
      <div style="text-align:right;flex-shrink:0;">
        <p style="font-size:15px;font-weight:700;color:var(--pine);margin:0;">
          Rp {{ number_format($order->price_paid ?? 0, 0, ',', '.') }}
        </p>
        @if(isset($order->savings_amount) && $order->savings_amount > 0)
          <p style="font-size:10px;color:var(--copper);margin:0;">
            Hemat Rp {{ number_format($order->savings_amount, 0, ',', '.') }}
          </p>
        @endif
      </div>
    </div>

    {{-- Pickup Code --}}
    @if($order->pickup_code && in_array($status, ['confirmed','siap diambil','picked_up']))
    <div style="background:var(--kombu);border-radius:10px;padding:12px 16px;display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
      <div>
        <p style="color:var(--dingley);font-size:10px;font-weight:600;margin:0 0 2px;letter-spacing:.05em;">KODE PICKUP</p>
        <p style="color:var(--brandy);font-size:22px;font-weight:700;letter-spacing:5px;margin:0;font-family:monospace;">
          {{ $order->pickup_code }}
        </p>
      </div>
      <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="var(--dingley)">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
      </svg>
    </div>
    @endif

    {{-- Timeline --}}
    @php
      $steps = ['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','selesai'=>'Selesai'];
      $stepKeys = array_keys($steps);
      $currentIdx = array_search($status, $stepKeys);
      if($status === 'siap diambil' || $status === 'sedang dikirim') $currentIdx = 1;
      if($status === 'picked_up') $currentIdx = 2;
      if($currentIdx === false) $currentIdx = 0;
    @endphp
    <div style="display:flex;align-items:center;">
      @foreach($steps as $key => $label)
      @php $idx = array_search($key, $stepKeys); $done = $idx <= $currentIdx; @endphp
      <div style="display:flex;align-items:center;flex:1;">
        <div style="display:flex;flex-direction:column;align-items:center;gap:3px;">
          <div style="width:22px;height:22px;border-radius:50%;background:{{ $done ? 'var(--dingley)' : '#e8e4dc' }};display:flex;align-items:center;justify-content:center;transition:.3s;">
            @if($done)
              <svg width="12" height="12" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
              </svg>
            @endif
          </div>
          <span style="font-size:10px;color:{{ $done ? 'var(--dingley)' : '#bbb' }};font-weight:{{ $done ? '600' : '400' }};white-space:nowrap;">
            {{ $label }}
          </span>
        </div>
        @if(!$loop->last)
          <div style="flex:1;height:2px;background:{{ $done && $idx < $currentIdx ? 'var(--dingley)' : '#e8e4dc' }};margin:0 6px;margin-bottom:14px;transition:.3s;"></div>
        @endif
      </div>
      @endforeach
    </div>

  </div>
  @empty
  <div id="emptyState" style="background:#fff;border-radius:16px;border:1px solid #EDE8DF;padding:60px 20px;text-align:center;">
    <div style="font-size:48px;margin-bottom:12px;">📦</div>
    <h3 style="font-size:17px;font-weight:700;color:var(--pine);margin:0 0 6px;">Belum ada pesanan</h3>
    <p style="font-size:13px;color:#888;margin:0 0 20px;">Yuk selamatkan makanan surplus pertamamu!</p>
    <a href="{{ route('home') }}"
       style="display:inline-block;background:var(--pine);color:var(--brandy);border-radius:12px;padding:10px 28px;font-size:13px;font-weight:700;text-decoration:none;">
      Cari Produk
    </a>
  </div>
  @endforelse
</div>

<div id="noResultMsg" style="display:none;background:#fff;border-radius:16px;border:1px solid #EDE8DF;padding:40px 20px;text-align:center;">
  <p style="font-size:14px;color:#888;margin:0;">Tidak ada pesanan dengan status ini.</p>
</div>

@endsection

@push('scripts')
<script>
function filterOrders(status, btn) {
  // Reset all buttons
  document.querySelectorAll('#orderList ~ div button, div button[onclick*="filterOrders"]').forEach(b => {
    b.style.background = 'transparent';
    b.style.color = 'var(--dingley)';
  });
  // Active button
  btn.style.background = 'var(--pine)';
  btn.style.color = '#fff';

  const items = document.querySelectorAll('.order-item');
  let visible = 0;
  items.forEach(item => {
    const s = item.dataset.status;
    const show = status === 'all' || s === status ||
      (status === 'selesai' && (s === 'selesai' || s === 'picked_up'));
    item.style.display = show ? 'block' : 'none';
    if(show) visible++;
  });

  const noMsg = document.getElementById('noResultMsg');
  noMsg.style.display = (visible === 0 && items.length > 0) ? 'block' : 'none';
}
</script>
@endpush
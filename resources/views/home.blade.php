@extends('layouts.consumer')
@section('title', 'Beranda — App.Surplus')
@section('page-title', 'Beranda')

@section('content')

{{-- HERO --}}
<div style="background:linear-gradient(135deg,var(--pine) 0%,#4A5E35 60%,var(--dingley) 100%);border-radius:16px;padding:28px 24px;margin-bottom:24px;position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;background:radial-gradient(circle at 80% 50%, rgba(222,197,158,.08) 0%, transparent 60%);"></div>
  <div style="position:relative;">
    <h1 style="color:#fff;font-size:clamp(18px,3vw,26px);font-weight:700;margin:0 0 8px;line-height:1.3;">
      Makanan Enak, Harga <span style="color:var(--brandy)">Lebih Hemat</span> 🌿
    </h1>
    <p style="color:rgba(255,255,255,.75);font-size:13px;margin:0 0 20px;max-width:420px;">
      Selamatkan surplus makanan dari restoran & café terdekat. Kurangi limbah, hemat lebih banyak.
    </p>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
      <div style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:10px 16px;">
        <div style="color:#fff;font-size:22px;font-weight:700;line-height:1;">{{ $stores->count() }}</div>
        <div style="color:rgba(255,255,255,.65);font-size:11px;margin-top:2px;">Merchant Aktif</div>
      </div>
      <div style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:10px 16px;">
        <div style="color:#fff;font-size:22px;font-weight:700;line-height:1;">{{ $latestProducts->count() }}</div>
        <div style="color:rgba(255,255,255,.65);font-size:11px;margin-top:2px;">Produk Tersedia</div>
      </div>
      <div style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:10px 16px;">
        <div style="color:var(--brandy);font-size:22px;font-weight:700;line-height:1;">{{ $urgentProducts->count() }}</div>
        <div style="color:rgba(255,255,255,.65);font-size:11px;margin-top:2px;">Segera Habis</div>
      </div>
    </div>
  </div>
</div>

{{-- SEARCH --}}
<form action="{{ route('home') }}" method="GET" style="margin-bottom:24px;">
  <div style="display:flex;gap:10px;">
    <div style="flex:1;position:relative;">
      <svg style="position:absolute;left:14px;top:50%;transform:translateY(-50%);" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#999">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
      </svg>
      <input type="text" name="search" value="{{ $search ?? '' }}"
        placeholder="Cari merchant atau makanan..."
        style="width:100%;padding:11px 16px 11px 44px;border:1.5px solid #EDE8DF;border-radius:12px;font-size:14px;outline:none;background:#fff;color:var(--pine);transition:.2s;"
        onfocus="this.style.borderColor='var(--dingley)'"
        onblur="this.style.borderColor='#EDE8DF'">
    </div>
    <button type="submit"
      style="padding:11px 20px;background:var(--pine);color:var(--brandy);border:none;border-radius:12px;font-size:14px;font-weight:600;cursor:pointer;white-space:nowrap;transition:.2s;"
      onmouseover="this.style.background='var(--kombu)'"
      onmouseout="this.style.background='var(--pine)'">
      Cari
    </button>
  </div>
</form>

{{-- URGENT PRODUCTS --}}
@if($urgentProducts->count() > 0)
<div style="margin-bottom:28px;">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
    <h2 style="font-size:15px;font-weight:700;color:var(--pine);display:flex;align-items:center;gap:8px;">
      <span style="width:4px;height:18px;background:var(--copper);border-radius:2px;display:block;"></span>
      ⚡ Segera Habis
    </h2>
    <span style="font-size:11px;color:var(--copper);font-weight:600;background:rgba(196,134,109,.1);padding:3px 10px;border-radius:20px;">
      Sisa &lt; 3 jam!
    </span>
  </div>
  <div style="display:flex;gap:12px;overflow-x:auto;padding-bottom:8px;scrollbar-width:none;">
    @foreach($urgentProducts as $product)
    <a href="{{ route('product.detail', $product->id) }}"
       style="flex-shrink:0;width:155px;background:#fff;border-radius:14px;border:1.5px solid #FFE0CC;overflow:hidden;text-decoration:none;transition:.2s;display:block;"
       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(196,134,109,.2)'"
       onmouseout="this.style.transform='';this.style.boxShadow=''">
      @if($product->image_url)
        <img src="{{ asset('storage/'.$product->image_url) }}" alt="{{ $product->name }}"
             style="width:100%;height:95px;object-fit:cover;background:#f0ebe3;">
      @else
        <div style="width:100%;height:95px;background:linear-gradient(135deg,#f5ede3,#ede3d5);display:flex;align-items:center;justify-content:center;">
          <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#C4866D">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
      @endif
      <div style="padding:8px 10px 10px;">
        <div style="background:#FF6B35;color:#fff;font-size:9px;font-weight:700;padding:2px 8px;border-radius:20px;display:inline-block;margin-bottom:5px;">
          {{ \Carbon\Carbon::parse($product->pickup_deadline)->diffForHumans() }}
        </div>
        <div style="font-size:12px;font-weight:600;color:var(--pine);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:3px;">
          {{ $product->name }}
        </div>
        <div style="display:flex;align-items:baseline;gap:5px;">
          <span style="font-size:13px;font-weight:700;color:var(--copper);">
            Rp {{ number_format($product->dynamic_price, 0, ',', '.') }}
          </span>
          @if($product->original_price > $product->dynamic_price)
          <span style="font-size:10px;color:#bbb;text-decoration:line-through;">
            Rp {{ number_format($product->original_price, 0, ',', '.') }}
          </span>
          @endif
        </div>
      </div>
    </a>
    @endforeach
  </div>
</div>
@endif

{{-- MERCHANT GRID --}}
<div>
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
    <h2 style="font-size:15px;font-weight:700;color:var(--pine);display:flex;align-items:center;gap:8px;">
      <span style="width:4px;height:18px;background:var(--brandy);border-radius:2px;display:block;"></span>
      @if($search)
        Hasil pencarian "{{ $search }}"
      @else
        Merchant Terdekat
      @endif
    </h2>
    @if($search)
    <a href="{{ route('home') }}" style="font-size:12px;color:var(--dingley);text-decoration:none;font-weight:500;">
      Lihat semua →
    </a>
    @endif
  </div>

  @if($stores->count() > 0)
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;">
    @foreach($stores as $store)
    <a href="{{ route('store.detail', $store->id) }}"
       style="background:#fff;border-radius:16px;border:1px solid #EDE8DF;overflow:hidden;text-decoration:none;display:block;transition:.2s;"
       onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 28px rgba(45,80,22,.12)'"
       onmouseout="this.style.transform='';this.style.boxShadow=''">
      <div style="background:linear-gradient(135deg,var(--pine),var(--dingley));padding:18px 18px 14px;position:relative;">
        <div style="width:44px;height:44px;background:rgba(255,255,255,.2);border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:#fff;margin-bottom:8px;">
          {{ strtoupper(substr($store->name, 0, 1)) }}
        </div>
        <p style="color:#fff;font-size:14px;font-weight:700;margin:0 0 3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
          {{ $store->name }}
        </p>
        <p style="color:rgba(255,255,255,.7);font-size:11px;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
          📍 {{ $store->address }}
        </p>
        @if(($store->active_products_count ?? 0) > 0)
          <div style="position:absolute;top:12px;right:12px;background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3);color:#fff;font-size:9px;font-weight:700;padding:3px 9px;border-radius:20px;">
            ✓ Buka
          </div>
        @else
          <div style="position:absolute;top:12px;right:12px;background:rgba(0,0,0,.2);color:rgba(255,255,255,.5);font-size:9px;font-weight:600;padding:3px 9px;border-radius:20px;">
            Kosong
          </div>
        @endif
      </div>
      <div style="padding:12px 16px;display:flex;align-items:center;justify-content:space-between;">
        <div style="font-size:13px;color:var(--dingley);font-weight:500;">
          <span style="font-size:20px;font-weight:700;color:var(--pine);margin-right:3px;">
            {{ $store->active_products_count ?? 0 }}
          </span>
          produk tersedia
        </div>
        <div style="background:var(--pine);color:#fff;font-size:12px;font-weight:600;padding:6px 14px;border-radius:20px;">
          Lihat →
        </div>
      </div>
    </a>
    @endforeach
  </div>
  @else
  <div style="text-align:center;padding:60px 20px;background:#fff;border-radius:16px;border:1px solid #EDE8DF;">
    <div style="font-size:48px;margin-bottom:12px;">🔍</div>
    <h3 style="font-size:17px;font-weight:700;color:var(--pine);margin:0 0 6px;">
      {{ $search ? 'Merchant tidak ditemukan' : 'Belum ada merchant' }}
    </h3>
    <p style="font-size:13px;color:#888;margin:0;">
      {{ $search ? 'Coba kata kunci lain' : 'Merchant akan muncul setelah diverifikasi admin' }}
    </p>
  </div>
  @endif
</div>

@endsection
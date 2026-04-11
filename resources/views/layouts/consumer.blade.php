<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'App.Surplus')</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
:root {
  --pine:#202808; --kombu:#33432B; --dingley:#6A784D;
  --brandy:#DEC59E; --copper:#C4866D; --cream:#F5F0E8;
}
* { box-sizing:border-box; margin:0; padding:0; }
body { background:#F7F4EE; font-family:'Inter',system-ui,sans-serif; }

/* SIDEBAR */
.sidebar {
  position:fixed; top:0; left:0; height:100vh; width:240px;
  background:var(--pine); display:flex; flex-direction:column;
  z-index:100; transition:transform .3s ease; overflow:hidden;
}
.sidebar-logo {
  padding:20px 20px 16px;
  border-bottom:1px solid rgba(255,255,255,.1);
  flex-shrink:0;
}
.sidebar-logo a { display:flex; align-items:center; gap:10px; text-decoration:none; }
.logo-box {
  width:36px; height:36px; background:var(--brandy);
  border-radius:9px; display:flex; align-items:center;
  justify-content:center; flex-shrink:0;
}
.logo-box span { color:var(--pine); font-size:16px; font-weight:800; }
.logo-text { color:#fff; font-size:16px; font-weight:700; }
.logo-text span { color:var(--brandy); }

.sidebar-user {
  padding:14px 20px;
  border-bottom:1px solid rgba(255,255,255,.08);
  display:flex; align-items:center; gap:12px;
  flex-shrink:0;
}
.user-avatar {
  width:38px; height:38px; border-radius:50%;
  background:var(--dingley); display:flex; align-items:center;
  justify-content:center; font-size:15px; font-weight:700;
  color:#fff; flex-shrink:0;
}
.user-name { color:#fff; font-size:13px; font-weight:600; line-height:1.3; }
.user-role { color:var(--brandy); font-size:10px; font-weight:500; margin-top:1px; }

.sidebar-nav { flex:1; padding:10px 0; overflow-y:auto; }
.sidebar-nav::-webkit-scrollbar { width:4px; }
.sidebar-nav::-webkit-scrollbar-thumb { background:rgba(255,255,255,.1); border-radius:2px; }

.nav-section {
  padding:10px 20px 4px;
  color:rgba(255,255,255,.35);
  font-size:10px; font-weight:600;
  text-transform:uppercase; letter-spacing:.08em;
}
.nav-item {
  display:flex; align-items:center; gap:10px;
  padding:10px 20px; color:rgba(255,255,255,.7);
  text-decoration:none; font-size:13px; font-weight:500;
  transition:.2s; position:relative; cursor:pointer;
}
.nav-item:hover { background:rgba(255,255,255,.08); color:#fff; }
.nav-item.active { background:rgba(222,197,158,.15); color:var(--brandy); }
.nav-item.active::before {
  content:''; position:absolute; left:0; top:0; bottom:0;
  width:3px; background:var(--brandy); border-radius:0 2px 2px 0;
}
.nav-badge {
  margin-left:auto; background:var(--copper); color:#fff;
  font-size:10px; font-weight:700; padding:2px 7px;
  border-radius:20px; min-width:20px; text-align:center;
}

.sidebar-footer {
  padding:14px 20px;
  border-top:1px solid rgba(255,255,255,.08);
  flex-shrink:0;
}
.logout-btn {
  display:flex; align-items:center; gap:8px; width:100%;
  background:rgba(196,134,109,.15); color:var(--copper);
  border:1px solid rgba(196,134,109,.2); border-radius:10px;
  padding:9px 14px; font-size:13px; font-weight:600;
  cursor:pointer; text-decoration:none; transition:.2s;
}
.logout-btn:hover { background:rgba(196,134,109,.25); }

/* MAIN CONTENT */
.main-wrap { margin-left:240px; min-height:100vh; transition:margin .3s ease; }

.topbar {
  background:#fff; border-bottom:1px solid #EDE8DF;
  padding:0 28px; height:60px; display:flex; align-items:center;
  justify-content:space-between; position:sticky; top:0; z-index:50;
  box-shadow:0 1px 8px rgba(0,0,0,.06);
}
.topbar-left { display:flex; align-items:center; gap:12px; }
.topbar-title { font-size:16px; font-weight:700; color:var(--pine); }
.topbar-right { display:flex; align-items:center; gap:14px; }
.topbar-search {
  display:flex; align-items:center; gap:8px;
  background:#F7F4EE; border:1px solid #EDE8DF;
  border-radius:22px; padding:7px 14px;
}
.topbar-search input {
  border:none; background:none; outline:none;
  font-size:13px; color:var(--pine); width:180px;
}
.topbar-cart {
  position:relative; color:var(--pine);
  text-decoration:none; display:flex; align-items:center;
}
.cart-dot {
  position:absolute; top:-4px; right:-6px;
  background:var(--copper); color:#fff;
  font-size:9px; font-weight:700;
  width:16px; height:16px; border-radius:50%;
  display:flex; align-items:center; justify-content:center;
}

.page-content { padding:28px; }

/* HAMBURGER */
.hamburger {
  display:none; background:none; border:none;
  cursor:pointer; padding:6px; border-radius:8px;
  transition:.2s;
}
.hamburger:hover { background:#F7F4EE; }

/* OVERLAY */
.sidebar-overlay {
  display:none; position:fixed; inset:0;
  background:rgba(0,0,0,.5); z-index:99;
}

/* BOTTOM NAV mobile */
.bottom-nav {
  display:none; position:fixed; bottom:0; left:0; right:0;
  background:var(--pine); border-top:1px solid var(--kombu);
  padding:6px 0 env(safe-area-inset-bottom, 8px); z-index:100;
}
.bottom-nav-inner { display:grid; grid-template-columns:repeat(4,1fr); }
.bottom-nav a {
  display:flex; flex-direction:column; align-items:center;
  gap:3px; text-decoration:none; padding:5px 0;
}
.bottom-nav a span { font-size:9px; color:rgba(255,255,255,.5); font-weight:500; }
.bottom-nav a.active span { color:var(--brandy); font-weight:700; }
.bottom-nav a svg { transition:.2s; }
.bottom-nav a.active svg { filter:brightness(1.5); }

/* RESPONSIVE */
@media(max-width:1024px) {
  .sidebar { width:220px; }
  .main-wrap { margin-left:220px; }
}

@media(max-width:768px) {
  .sidebar { transform:translateX(-100%); width:260px; }
  .sidebar.open { transform:translateX(0); box-shadow:4px 0 20px rgba(0,0,0,.3); }
  .sidebar-overlay.open { display:block; }
  .main-wrap { margin-left:0; }
  .hamburger { display:flex; }
  .topbar { padding:0 16px; }
  .topbar-search { display:none; }
  .topbar-title { font-size:14px; }
  .bottom-nav { display:block; }
  .page-content { padding:16px 16px 80px; }
}

@media(max-width:480px) {
  .page-content { padding:12px 12px 80px; }
  .topbar { height:54px; }
}
</style>
@stack('styles')
</head>
<body>

{{-- OVERLAY --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- SIDEBAR --}}
<aside class="sidebar" id="sidebar">

  {{-- LOGO --}}
  <div class="sidebar-logo">
    <a href="{{ route('home') }}">
      <div class="logo-box"><span>S</span></div>
      <span class="logo-text">App.<span>Surplus</span></span>
    </a>
  </div>

  {{-- USER INFO --}}
  <div class="sidebar-user">
    <div class="user-avatar">
      {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    </div>
    <div style="min-width:0;">
      <div class="user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
        {{ auth()->user()->name }}
      </div>
      <div class="user-role">🌿 Consumer</div>
    </div>
  </div>

  {{-- NAV --}}
  <nav class="sidebar-nav">

    <div class="nav-section">Menu Utama</div>

    <a href="{{ route('home') }}"
       class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
      </svg>
      Beranda
    </a>

    <a href="{{ route('cart.index') }}"
       class="nav-item {{ request()->routeIs('cart.index') ? 'active' : '' }}">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
      </svg>
      Keranjang
      @php
        $cartCount = \App\Models\Cart::where('user_id', auth()->id())
          ->whereIn('status', ['pending','pending_expired'])->count();
      @endphp
      @if($cartCount > 0)
        <span class="nav-badge">{{ $cartCount }}</span>
      @endif
    </a>

    <a href="{{ route('my-orders.index') }}"
       class="nav-item {{ request()->routeIs('my-orders.index') ? 'active' : '' }}">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
      </svg>
      Pesanan Saya
      @php
        $pendingOrders = \App\Models\Transaction::where('user_id', auth()->id())
          ->where('status','pending')->count();
      @endphp
      @if($pendingOrders > 0)
        <span class="nav-badge">{{ $pendingOrders }}</span>
      @endif
    </a>

    <a href="{{ route('profile.edit') }}"
       class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
      </svg>
      Profil Saya
    </a>

    <div class="nav-section" style="margin-top:6px;">Kategori</div>

    <a href="{{ route('home') }}?search=bakery"
       class="nav-item">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.701 2.701 0 01-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"/>
      </svg>
      Bakery & Kue
    </a>

    <a href="{{ route('home') }}?search=seafood"
       class="nav-item">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
      </svg>
      Seafood
    </a>

    <a href="{{ route('home') }}?search=rawon"
       class="nav-item">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
      </svg>
      Makanan Berat
    </a>

    <a href="{{ route('home') }}?search=minuman"
       class="nav-item">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M9 3v2m6-2v2M9 19h6m-7 2h8a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
      </svg>
      Minuman
    </a>

  </nav>

  {{-- FOOTER --}}
  <div class="sidebar-footer">
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" class="logout-btn">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
        </svg>
        Keluar dari Akun
      </button>
    </form>
  </div>

</aside>

{{-- MAIN --}}
<div class="main-wrap" id="mainWrap">

  {{-- TOPBAR --}}
  <header class="topbar">
    <div class="topbar-left">
      <button class="hamburger" onclick="toggleSidebar()" aria-label="Toggle menu">
        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="var(--pine)">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <span class="topbar-title">@yield('page-title', 'App.Surplus')</span>
    </div>
    <div class="topbar-right">
      <div class="topbar-search">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#999">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
        </svg>
        <input type="text" placeholder="Cari produk atau merchant..."
          onkeydown="if(event.key==='Enter'){window.location='{{ route('home') }}?search='+this.value}">
      </div>
      <a href="{{ route('cart.index') }}" class="topbar-cart">
        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="var(--pine)">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        @if(isset($cartCount) && $cartCount > 0)
          <span class="cart-dot">{{ $cartCount }}</span>
        @endif
      </a>
    </div>
  </header>

  {{-- PAGE CONTENT --}}
  <main class="page-content">
    @if(session('success'))
      <div style="background:#F0F7EB;border:1px solid #C8E0B0;border-radius:10px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:8px;font-size:13px;color:#2D6016;">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div style="background:#FFF0F0;border:1px solid #FFD0D0;border-radius:10px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:8px;font-size:13px;color:#cc3333;">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
      </div>
    @endif
    @yield('content')
  </main>

</div>

{{-- BOTTOM NAV --}}
<nav class="bottom-nav">
  <div class="bottom-nav-inner">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="{{ request()->routeIs('home') ? 'var(--brandy)' : 'rgba(255,255,255,.55)' }}">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
      </svg>
      <span>Beranda</span>
    </a>
    <a href="{{ route('cart.index') }}" class="{{ request()->routeIs('cart.index') ? 'active' : '' }}" style="position:relative;">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="{{ request()->routeIs('cart.index') ? 'var(--brandy)' : 'rgba(255,255,255,.55)' }}">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
      </svg>
      <span>Keranjang</span>
      @if(isset($cartCount) && $cartCount > 0)
        <span style="position:absolute;top:2px;right:20%;background:var(--copper);color:#fff;font-size:8px;font-weight:700;width:14px;height:14px;border-radius:50%;display:flex;align-items:center;justify-content:center;">{{ $cartCount }}</span>
      @endif
    </a>
    <a href="{{ route('my-orders.index') }}" class="{{ request()->routeIs('my-orders.index') ? 'active' : '' }}">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="{{ request()->routeIs('my-orders.index') ? 'var(--brandy)' : 'rgba(255,255,255,.55)' }}">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
      </svg>
      <span>Pesanan</span>
    </a>
    <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="{{ request()->routeIs('profile.edit') ? 'var(--brandy)' : 'rgba(255,255,255,.55)' }}">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
      </svg>
      <span>Profil</span>
    </a>
  </div>
</nav>

<script>
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  sidebar.classList.toggle('open');
  overlay.classList.toggle('open');
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('sidebarOverlay').classList.remove('open');
}
// Close sidebar on ESC
document.addEventListener('keydown', e => { if(e.key === 'Escape') closeSidebar(); });
</script>
@stack('scripts')
</body>
</html>
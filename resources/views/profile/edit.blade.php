<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Profil Saya — App.Surplus</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  :root {
    --pine:#202808; --kombu:#33432B; --dingley:#6A784D;
    --brandy:#DEC59E; --copper:#C4866D; --cream:#F5F0E8;
  }
  * { box-sizing:border-box; }
  body { background:#F7F4EE; font-family:'Inter',system-ui,sans-serif; margin:0; }

  .navbar { background:var(--pine); position:sticky; top:0; z-index:50; box-shadow:0 2px 12px rgba(0,0,0,.25); }
  .navbar-inner { max-width:700px; margin:0 auto; padding:0 20px; height:60px; display:flex; align-items:center; gap:12px; }
  .back-btn { display:flex; align-items:center; gap:6px; color:rgba(255,255,255,.8); text-decoration:none; font-size:14px; }
  .back-btn:hover { color:#fff; }
  .nav-title { color:#fff; font-size:15px; font-weight:600; flex:1; text-align:center; }

  .page-wrap { max-width:700px; margin:0 auto; padding:24px 20px 100px; }

  /* PROFILE HEADER */
  .profile-header { background:linear-gradient(135deg, var(--pine) 0%, var(--kombu) 60%, var(--dingley) 100%); border-radius:20px; padding:28px 24px; display:flex; align-items:center; gap:20px; margin-bottom:20px; }
  .avatar-wrap { position:relative; flex-shrink:0; }
  .avatar { width:72px; height:72px; border-radius:50%; border:3px solid var(--brandy); object-fit:cover; background:rgba(255,255,255,.15); display:flex; align-items:center; justify-content:center; font-size:28px; font-weight:700; color:var(--brandy); overflow:hidden; }
  .avatar img { width:100%; height:100%; object-fit:cover; }
  .avatar-edit { position:absolute; bottom:0; right:0; width:24px; height:24px; background:var(--brandy); border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; border:2px solid var(--pine); }
  .profile-info h2 { color:#fff; font-size:18px; font-weight:700; margin:0 0 3px; }
  .profile-info p { color:rgba(255,255,255,.65); font-size:13px; margin:0 0 10px; }
  .profile-role { display:inline-flex; align-items:center; gap:5px; background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.2); color:var(--brandy); font-size:11px; font-weight:600; padding:4px 10px; border-radius:20px; }

  /* STATS */
  .stats-row { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:20px; }
  .stat-card { background:#fff; border-radius:14px; border:1px solid #EDE8DF; padding:14px; text-align:center; }
  .stat-num { font-size:20px; font-weight:700; color:var(--pine); }
  .stat-lbl { font-size:11px; color:#888; margin-top:2px; }

  /* FORM SECTION */
  .form-section { background:#fff; border-radius:16px; border:1px solid #EDE8DF; overflow:hidden; margin-bottom:16px; }
  .form-section-header { padding:14px 18px; border-bottom:1px solid #F0EDE8; display:flex; align-items:center; gap:8px; }
  .form-section-title { font-size:14px; font-weight:700; color:var(--pine); }
  .form-section-body { padding:18px; }
  .form-group { margin-bottom:16px; }
  .form-group:last-child { margin-bottom:0; }
  .form-label { display:block; font-size:12px; font-weight:600; color:var(--dingley); text-transform:uppercase; letter-spacing:.04em; margin-bottom:6px; }
  .form-input { width:100%; padding:11px 14px; border:1.5px solid #E0DDD8; border-radius:10px; font-size:14px; color:var(--pine); background:#fff; outline:none; transition:.2s; font-family:inherit; }
  .form-input:focus { border-color:var(--dingley); box-shadow:0 0 0 3px rgba(106,120,77,.1); }
  .form-input:disabled { background:#F7F4EE; color:#aaa; cursor:not-allowed; }
  .form-hint { font-size:11px; color:#aaa; margin-top:4px; }

  .save-btn { width:100%; padding:13px; background:var(--pine); color:var(--brandy); border:none; border-radius:12px; font-size:15px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:.2s; }
  .save-btn:hover { background:var(--kombu); }

  /* DANGER ZONE */
  .danger-section { background:#fff; border-radius:16px; border:1.5px solid #FFE0E0; overflow:hidden; margin-bottom:16px; }
  .danger-header { padding:14px 18px; border-bottom:1px solid #FFF0F0; display:flex; align-items:center; gap:8px; }
  .danger-title { font-size:14px; font-weight:700; color:#cc3333; }
  .danger-body { padding:18px; }
  .logout-btn { width:100%; padding:11px; background:#FFF5F5; color:#cc3333; border:1.5px solid #FFD0D0; border-radius:10px; font-size:14px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:7px; transition:.2s; margin-bottom:10px; }
  .logout-btn:hover { background:#FFE8E8; }

  /* ALERTS */
  .alert { padding:12px 16px; border-radius:10px; font-size:13px; margin-bottom:16px; display:flex; align-items:center; gap:8px; }
  .alert-success { background:#F0F7EB; color:#2D6016; border:1px solid #C8E0B0; }
  .alert-error { background:#FFF0F0; color:#cc3333; border:1px solid #FFD0D0; }

  .bottom-nav { position:fixed; bottom:0; left:0; right:0; background:var(--pine); border-top:1px solid var(--kombu); padding:6px 0 8px; z-index:50; }
  .bottom-nav-inner { display:grid; grid-template-columns:repeat(4,1fr); max-width:700px; margin:0 auto; }
  .bottom-nav a { display:flex; flex-direction:column; align-items:center; gap:3px; text-decoration:none; padding:4px 0; }
  .bottom-nav a span { font-size:9px; color:rgba(255,255,255,.55); }
  .bottom-nav a.active span { color:var(--brandy); font-weight:600; }
</style>
</head>
<body>

<nav class="navbar">
  <div class="navbar-inner">
    <a href="{{ route('home') }}" class="back-btn">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      Kembali
    </a>
    <span class="nav-title">Profil Saya</span>
    <div style="width:60px"></div>
  </div>
</nav>

<div class="page-wrap">

  {{-- SUCCESS / ERROR ALERTS --}}
  @if(session('status') === 'profile-updated')
  <div class="alert alert-success">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    Profil berhasil diperbarui!
  </div>
  @endif
  @if($errors->any())
  <div class="alert alert-error">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ $errors->first() }}
  </div>
  @endif

  {{-- PROFILE HEADER --}}
  <div class="profile-header">
    <div class="avatar-wrap">
      <div class="avatar">
        @if(auth()->user()->profile_photo_path)
          <img src="{{ asset('storage/'.auth()->user()->profile_photo_path) }}" alt="Foto profil">
        @else
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        @endif
      </div>
    </div>
    <div class="profile-info">
      <h2>{{ auth()->user()->name }}</h2>
      <p>{{ auth()->user()->email }}</p>
      <div class="profile-role">
        🌿 {{ ucfirst(auth()->user()->role ?? 'consumer') }}
      </div>
    </div>
  </div>

  {{-- STATS --}}
  @php
    $orderCount = \App\Models\Transaction::where('user_id', auth()->id())->count();
    $totalSavings = \App\Models\Transaction::where('user_id', auth()->id())->sum('savings_amount');
    $totalSpent = \App\Models\Transaction::where('user_id', auth()->id())->sum('price_paid');
  @endphp
  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-num">{{ $orderCount }}</div>
      <div class="stat-lbl">Pesanan</div>
    </div>
    <div class="stat-card">
      <div class="stat-num" style="font-size:15px;color:var(--copper)">Rp {{ number_format($totalSavings/1000, 0, ',', '.')  }}k</div>
      <div class="stat-lbl">Total Hemat</div>
    </div>
    <div class="stat-card">
      <div class="stat-num" style="font-size:15px">Rp {{ number_format($totalSpent/1000, 0, ',', '.') }}k</div>
      <div class="stat-lbl">Total Belanja</div>
    </div>
  </div>

  {{-- FORM UPDATE PROFIL --}}
  <div class="form-section">
    <div class="form-section-header">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="var(--dingley)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
      <span class="form-section-title">Informasi Pribadi</span>
    </div>
    <div class="form-section-body">
      <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="form-group">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="name" class="form-input" value="{{ old('name', auth()->user()->name) }}" required>
        </div>
        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-input" value="{{ old('email', auth()->user()->email) }}" required>
          @if(auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
            <p class="form-hint" style="color:var(--copper)">⚠️ Email belum diverifikasi.</p>
          @endif
        </div>
        <div class="form-group">
          <label class="form-label">Nomor Telepon</label>
          <input type="tel" name="phone" class="form-input" value="{{ old('phone', auth()->user()->phone ?? '') }}" placeholder="08xxxxxxxxxx">
        </div>
        <div class="form-group">
          <label class="form-label">Alamat</label>
          <textarea name="address" class="form-input" rows="3" placeholder="Masukkan alamat lengkap kamu">{{ old('address', auth()->user()->address ?? '') }}</textarea>
        </div>
        <button type="submit" class="save-btn">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          Simpan Perubahan
        </button>
      </form>
    </div>
  </div>

  {{-- GANTI PASSWORD --}}
  <div class="form-section">
    <div class="form-section-header">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="var(--dingley)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
      <span class="form-section-title">Ganti Password</span>
    </div>
    <div class="form-section-body">
      <form action="{{ route('password.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label class="form-label">Password Saat Ini</label>
          <input type="password" name="current_password" class="form-input" placeholder="••••••••">
        </div>
        <div class="form-group">
          <label class="form-label">Password Baru</label>
          <input type="password" name="password" class="form-input" placeholder="••••••••">
          <p class="form-hint">Minimal 8 karakter</p>
        </div>
        <div class="form-group">
          <label class="form-label">Konfirmasi Password Baru</label>
          <input type="password" name="password_confirmation" class="form-input" placeholder="••••••••">
        </div>
        <button type="submit" class="save-btn" style="background:var(--dingley);">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
          Update Password
        </button>
      </form>
    </div>
  </div>

  {{-- DANGER ZONE --}}
  <div class="danger-section">
    <div class="danger-header">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#cc3333"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
      <span class="danger-title">Zona Berbahaya</span>
    </div>
    <div class="danger-body">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="logout-btn">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
          Keluar dari Akun
        </button>
      </form>
    </div>
  </div>

</div>

{{-- BOTTOM NAV --}}
<nav class="bottom-nav">
  <div class="bottom-nav-inner">
    <a href="{{ route('home') }}">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.6)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
      <span>Beranda</span>
    </a>
    <a href="{{ route('cart.index') }}">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.6)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      <span>Keranjang</span>
    </a>
    <a href="{{ route('my-orders.index') }}">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.6)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      <span>Pesanan</span>
    </a>
    <a href="{{ route('profile.edit') }}" class="active">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="var(--brandy)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
      <span style="color:var(--brandy)">Profil</span>
    </a>
  </div>
</nav>

</body>
</html>
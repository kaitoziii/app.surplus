@extends('layouts.consumer')
@section('title', 'Profil Saya — App.Surplus')
@section('page-title', 'Profil Saya')

@section('content')

@php
  $orderCount   = \App\Models\Transaction::where('user_id', auth()->id())->count();
  $totalSavings = \App\Models\Transaction::where('user_id', auth()->id())->sum('savings_amount');
  $totalSpent   = \App\Models\Transaction::where('user_id', auth()->id())->sum('price_paid');
@endphp

<div style="max-width:700px;margin:0 auto;">

  {{-- PROFILE HEADER --}}
  <div style="background:linear-gradient(135deg,var(--pine) 0%,var(--kombu) 60%,var(--dingley) 100%);border-radius:20px;padding:28px 24px;display:flex;align-items:center;gap:20px;margin-bottom:20px;flex-wrap:wrap;">
    <div style="position:relative;flex-shrink:0;">
      <div style="width:72px;height:72px;border-radius:50%;border:3px solid var(--brandy);background:var(--dingley);display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:700;color:#fff;overflow:hidden;">
        @if(auth()->user()->profile_photo_path)
          <img src="{{ asset('storage/'.auth()->user()->profile_photo_path) }}" style="width:100%;height:100%;object-fit:cover;">
        @else
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        @endif
      </div>
    </div>
    <div>
      <h2 style="color:#fff;font-size:18px;font-weight:700;margin:0 0 3px;">{{ auth()->user()->name }}</h2>
      <p style="color:rgba(255,255,255,.65);font-size:13px;margin:0 0 10px;">{{ auth()->user()->email }}</p>
      <div style="display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);color:var(--brandy);font-size:11px;font-weight:600;padding:4px 12px;border-radius:20px;">
        🌿 Consumer
      </div>
    </div>
  </div>

  {{-- STATS --}}
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;">
    <div style="background:#fff;border-radius:14px;border:1px solid #EDE8DF;padding:16px;text-align:center;">
      <div style="font-size:22px;font-weight:700;color:var(--pine);">{{ $orderCount }}</div>
      <div style="font-size:11px;color:#888;margin-top:3px;">Pesanan</div>
    </div>
    <div style="background:#fff;border-radius:14px;border:1px solid #EDE8DF;padding:16px;text-align:center;">
      <div style="font-size:16px;font-weight:700;color:var(--copper);">
        Rp {{ number_format($totalSavings/1000, 1, ',', '.') }}k
      </div>
      <div style="font-size:11px;color:#888;margin-top:3px;">Total Hemat</div>
    </div>
    <div style="background:#fff;border-radius:14px;border:1px solid #EDE8DF;padding:16px;text-align:center;">
      <div style="font-size:16px;font-weight:700;color:var(--pine);">
        Rp {{ number_format($totalSpent/1000, 1, ',', '.') }}k
      </div>
      <div style="font-size:11px;color:#888;margin-top:3px;">Total Belanja</div>
    </div>
  </div>

  {{-- UPDATE PROFIL --}}
  <div style="background:#fff;border-radius:16px;border:1px solid #EDE8DF;overflow:hidden;margin-bottom:16px;">
    <div style="padding:14px 20px;border-bottom:1px solid #F0EDE8;display:flex;align-items:center;gap:8px;">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="var(--dingley)">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
      </svg>
      <span style="font-size:14px;font-weight:700;color:var(--pine);">Informasi Pribadi</span>
    </div>
    <div style="padding:20px;">
      <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PATCH')

        @if(session('status') === 'profile-updated')
        <div style="background:#F0F7EB;border:1px solid #C8E0B0;border-radius:10px;padding:11px 14px;margin-bottom:16px;font-size:13px;color:#2D6016;display:flex;align-items:center;gap:7px;">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Profil berhasil diperbarui!
        </div>
        @endif
        @if($errors->any())
        <div style="background:#FFF0F0;border:1px solid #FFD0D0;border-radius:10px;padding:11px 14px;margin-bottom:16px;font-size:13px;color:#cc3333;">
          {{ $errors->first() }}
        </div>
        @endif

        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:11px;font-weight:600;color:var(--dingley);text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px;">Nama Lengkap</label>
          <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
            style="width:100%;padding:11px 14px;border:1.5px solid #E0DDD8;border-radius:10px;font-size:14px;color:var(--pine);outline:none;transition:.2s;font-family:inherit;"
            onfocus="this.style.borderColor='var(--dingley)'" onblur="this.style.borderColor='#E0DDD8'">
        </div>

        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:11px;font-weight:600;color:var(--dingley);text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px;">Email</label>
          <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
            style="width:100%;padding:11px 14px;border:1.5px solid #E0DDD8;border-radius:10px;font-size:14px;color:var(--pine);outline:none;transition:.2s;font-family:inherit;"
            onfocus="this.style.borderColor='var(--dingley)'" onblur="this.style.borderColor='#E0DDD8'">
        </div>

        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:11px;font-weight:600;color:var(--dingley);text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px;">Nomor Telepon</label>
          <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}"
            placeholder="08xxxxxxxxxx"
            style="width:100%;padding:11px 14px;border:1.5px solid #E0DDD8;border-radius:10px;font-size:14px;color:var(--pine);outline:none;transition:.2s;font-family:inherit;"
            onfocus="this.style.borderColor='var(--dingley)'" onblur="this.style.borderColor='#E0DDD8'">
        </div>

        <div style="margin-bottom:20px;">
          <label style="display:block;font-size:11px;font-weight:600;color:var(--dingley);text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px;">Alamat</label>
          <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap kamu"
            style="width:100%;padding:11px 14px;border:1.5px solid #E0DDD8;border-radius:10px;font-size:14px;color:var(--pine);outline:none;transition:.2s;font-family:inherit;resize:vertical;"
            onfocus="this.style.borderColor='var(--dingley)'" onblur="this.style.borderColor='#E0DDD8'">{{ old('address', auth()->user()->address ?? '') }}</textarea>
        </div>

        <button type="submit"
          style="width:100%;padding:13px;background:var(--pine);color:var(--brandy);border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:.2s;"
          onmouseover="this.style.background='var(--kombu)'"
          onmouseout="this.style.background='var(--pine)'">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          Simpan Perubahan
        </button>
      </form>
    </div>
  </div>

  {{-- GANTI PASSWORD --}}
  <div style="background:#fff;border-radius:16px;border:1px solid #EDE8DF;overflow:hidden;margin-bottom:16px;">
    <div style="padding:14px 20px;border-bottom:1px solid #F0EDE8;display:flex;align-items:center;gap:8px;">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="var(--dingley)">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
      </svg>
      <span style="font-size:14px;font-weight:700;color:var(--pine);">Ganti Password</span>
    </div>
    <div style="padding:20px;">
      <form action="{{ route('password.update') }}" method="POST">
        @csrf
        @method('PUT')

        @if(session('status') === 'password-updated')
        <div style="background:#F0F7EB;border:1px solid #C8E0B0;border-radius:10px;padding:11px 14px;margin-bottom:16px;font-size:13px;color:#2D6016;">
          Password berhasil diperbarui!
        </div>
        @endif

        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:11px;font-weight:600;color:var(--dingley);text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px;">Password Saat Ini</label>
          <input type="password" name="current_password" placeholder="••••••••"
            style="width:100%;padding:11px 14px;border:1.5px solid #E0DDD8;border-radius:10px;font-size:14px;outline:none;transition:.2s;font-family:inherit;"
            onfocus="this.style.borderColor='var(--dingley)'" onblur="this.style.borderColor='#E0DDD8'">
        </div>
        <div style="margin-bottom:14px;">
          <label style="display:block;font-size:11px;font-weight:600;color:var(--dingley);text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px;">Password Baru</label>
          <input type="password" name="password" placeholder="••••••••"
            style="width:100%;padding:11px 14px;border:1.5px solid #E0DDD8;border-radius:10px;font-size:14px;outline:none;transition:.2s;font-family:inherit;"
            onfocus="this.style.borderColor='var(--dingley)'" onblur="this.style.borderColor='#E0DDD8'">
          <p style="font-size:11px;color:#aaa;margin:4px 0 0;">Minimal 8 karakter</p>
        </div>
        <div style="margin-bottom:20px;">
          <label style="display:block;font-size:11px;font-weight:600;color:var(--dingley);text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px;">Konfirmasi Password</label>
          <input type="password" name="password_confirmation" placeholder="••••••••"
            style="width:100%;padding:11px 14px;border:1.5px solid #E0DDD8;border-radius:10px;font-size:14px;outline:none;transition:.2s;font-family:inherit;"
            onfocus="this.style.borderColor='var(--dingley)'" onblur="this.style.borderColor='#E0DDD8'">
        </div>
        <button type="submit"
          style="width:100%;padding:13px;background:var(--dingley);color:#fff;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;transition:.2s;"
          onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
          Update Password
        </button>
      </form>
    </div>
  </div>

  {{-- DANGER --}}
  <div style="background:#fff;border-radius:16px;border:1.5px solid #FFE0E0;overflow:hidden;">
    <div style="padding:14px 20px;border-bottom:1px solid #FFF0F0;display:flex;align-items:center;gap:8px;">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#cc3333">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
      </svg>
      <span style="font-size:14px;font-weight:700;color:#cc3333;">Zona Berbahaya</span>
    </div>
    <div style="padding:20px;">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit"
          style="width:100%;padding:11px;background:#FFF5F5;color:#cc3333;border:1.5px solid #FFD0D0;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;transition:.2s;"
          onmouseover="this.style.background='#FFE8E8'" onmouseout="this.style.background='#FFF5F5'">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          Keluar dari Akun
        </button>
      </form>
    </div>
  </div>

</div>
@endsection
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404 — Halaman Tidak Ditemukan | App.Surplus</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&display=swap');
  body { font-family: 'Plus Jakarta Sans', sans-serif; }
  .mascot-wrap { filter: drop-shadow(0 20px 40px rgba(0,0,0,0.12)); }
  .float { animation: float 3.5s ease-in-out infinite; }
  @keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-12px); }
  }
  .bubble {
    position: absolute;
    background: white;
    border: 1.5px solid #dcfce7;
    border-radius: 16px 16px 16px 4px;
    padding: 8px 14px;
    font-size: 12px;
    color: #16a34a;
    font-weight: 500;
    white-space: nowrap;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
  }
  .badge-404 {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border: 1.5px solid #bbf7d0;
  }
</style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-white to-green-50 min-h-screen flex items-center justify-center">

<div class="max-w-4xl w-full mx-auto px-8 flex items-center gap-16">

  {{-- Kiri: Teks --}}
  <div class="flex-1 text-left">

    {{-- Badge --}}
    <div class="inline-flex items-center gap-2 badge-404 rounded-full px-4 py-1.5 mb-6">
      <span class="w-2 h-2 bg-green-400 rounded-full"></span>
      <span class="text-xs font-medium text-green-700">App.Surplus</span>
    </div>

    {{-- Kode error besar --}}
    <div class="flex items-baseline gap-1 mb-2">
      <span class="text-8xl font-semibold text-gray-100 leading-none select-none">4</span>
      <span class="text-8xl font-semibold text-green-200 leading-none select-none">0</span>
      <span class="text-8xl font-semibold text-gray-100 leading-none select-none">4</span>
    </div>

    {{-- Judul --}}
    <h1 class="text-2xl font-semibold text-gray-800 mb-3 -mt-2">
      Halaman Tidak Ditemukan
    </h1>

    {{-- Deskripsi --}}
    <p class="text-sm text-gray-400 leading-relaxed mb-8 max-w-sm">
      Sepertinya makanan yang kamu cari sudah <span class="text-green-600 font-medium">habis terjual</span> sebelum kamu sampai! Halaman ini tidak ada atau sudah dipindahkan.
    </p>

    {{-- Suggestion list --}}
    <div class="space-y-2 mb-8">
      <p class="text-xs font-medium text-gray-500 mb-3">Mungkin kamu mencari:</p>
      <a href="/" class="flex items-center gap-3 group">
        <div class="w-7 h-7 bg-green-50 border border-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-100 transition-colors">
          <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
        </div>
        <span class="text-xs text-gray-600 group-hover:text-green-600 transition-colors">Halaman Utama</span>
      </a>
      <a href="/admin/dashboard" class="flex items-center gap-3 group">
        <div class="w-7 h-7 bg-green-50 border border-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-100 transition-colors">
          <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
        </div>
        <span class="text-xs text-gray-600 group-hover:text-green-600 transition-colors">Dashboard Admin</span>
      </a>
      <a href="/admin/merchants" class="flex items-center gap-3 group">
        <div class="w-7 h-7 bg-green-50 border border-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-100 transition-colors">
          <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
          </svg>
        </div>
        <span class="text-xs text-gray-600 group-hover:text-green-600 transition-colors">Verifikasi Merchant</span>
      </a>
    </div>

    {{-- Buttons --}}
    <div class="flex gap-3">
      <button onclick="history.back()"
        class="text-sm px-5 py-2.5 rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors">
        ← Kembali
      </button>
      <a href="/"
        class="text-sm px-5 py-2.5 rounded-xl bg-green-600 text-white hover:bg-green-700 transition-colors">
        Ke Beranda
      </a>
    </div>

    {{-- Footer --}}
    <p class="text-xs text-gray-300 mt-10">© {{ date('Y') }} App.Surplus — Mengurangi food waste bersama</p>
  </div>

  {{-- Kanan: Maskot --}}
  <div class="flex-shrink-0 relative w-80">

    {{-- Speech bubble --}}
    <div class="bubble" style="top: 10px; right: 0px;">
      Aduh, halamannya habis! 😅
    </div>

    {{-- Mascot --}}
    <div class="mascot-wrap float mt-12">
      <img src="/images/maskot.png" alt="App.Surplus Mascot"
        class="w-72 h-72 object-contain mx-auto">
    </div>

    {{-- Decorative circles --}}
    <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-48 h-6 bg-black/5 rounded-full blur-xl"></div>
  </div>

</div>
</body>
</html>
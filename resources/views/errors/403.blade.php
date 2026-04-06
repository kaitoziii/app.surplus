<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>403 — Akses Ditolak | App.Surplus</title>
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
    border: 1.5px solid #fee2e2;
    border-radius: 16px 16px 16px 4px;
    padding: 8px 14px;
    font-size: 12px;
    color: #dc2626;
    font-weight: 500;
    white-space: nowrap;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
  }
  .badge-403 {
    background: linear-gradient(135deg, #fff7ed, #ffedd5);
    border: 1.5px solid #fed7aa;
  }
</style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-white to-red-50 min-h-screen flex items-center justify-center">

<div class="max-w-4xl w-full mx-auto px-8 flex items-center gap-16">

  {{-- Kiri: Teks --}}
  <div class="flex-1 text-left">

    {{-- Badge --}}
    <div class="inline-flex items-center gap-2 badge-403 rounded-full px-4 py-1.5 mb-6">
      <span class="w-2 h-2 bg-orange-400 rounded-full"></span>
      <span class="text-xs font-medium text-orange-700">App.Surplus</span>
    </div>

    {{-- Kode error besar --}}
    <div class="flex items-baseline gap-1 mb-2">
      <span class="text-8xl font-semibold text-gray-100 leading-none select-none">4</span>
      <span class="text-8xl font-semibold text-red-200 leading-none select-none">0</span>
      <span class="text-8xl font-semibold text-gray-100 leading-none select-none">3</span>
    </div>

    {{-- Judul --}}
    <h1 class="text-2xl font-semibold text-gray-800 mb-3 -mt-2">
      Akses Ditolak
    </h1>

    {{-- Deskripsi --}}
    <p class="text-sm text-gray-400 leading-relaxed mb-8 max-w-sm">
      Kamu tidak memiliki izin untuk mengakses halaman ini. Halaman ini hanya dapat diakses oleh <span class="text-red-500 font-medium">Admin</span> yang sudah login.
    </p>

    {{-- Info box --}}
    <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 mb-8 max-w-sm">
      <div class="flex items-start gap-3">
        <div class="w-5 h-5 bg-amber-100 rounded-full flex items-center justify-center shrink-0 mt-0.5">
          <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <p class="text-xs font-medium text-amber-700 mb-0.5">Kenapa ini terjadi?</p>
          <p class="text-xs text-amber-600 leading-relaxed">
            Kamu belum login sebagai admin, atau sesi kamu telah berakhir. Silakan login terlebih dahulu.
          </p>
        </div>
      </div>
    </div>

    {{-- Buttons --}}
    <div class="flex gap-3">
      <button onclick="history.back()"
        class="text-sm px-5 py-2.5 rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors">
        ← Kembali
      </button>
      <a href="/login"
        class="text-sm px-5 py-2.5 rounded-xl bg-green-600 text-white hover:bg-green-700 transition-colors">
        Login Sekarang
      </a>
    </div>

    {{-- Footer --}}
    <p class="text-xs text-gray-300 mt-10">© {{ date('Y') }} App.Surplus — Mengurangi food waste bersama</p>
  </div>

  {{-- Kanan: Maskot --}}
  <div class="flex-shrink-0 relative w-80">

    {{-- Speech bubble --}}
    <div class="bubble" style="top: 10px; right: 0px;">
      Eh, kamu tidak boleh masuk! 🚫
    </div>

    {{-- Mascot --}}
    <div class="mascot-wrap float mt-12">
      <img src="/images/maskot.png" alt="App.Surplus Mascot"
        class="w-72 h-72 object-contain mx-auto">
    </div>

    {{-- Shadow --}}
    <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-48 h-6 bg-black/5 rounded-full blur-xl"></div>
  </div>

</div>
</body>
</html>
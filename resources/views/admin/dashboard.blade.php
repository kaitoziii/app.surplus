@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')

{{-- Row 1: Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    <div class="bg-white rounded-xl border border-gray-100 p-4 stat-card">
        <p class="text-xs text-gray-400 mb-1">Total Merchant</p>
        <p class="text-2xl font-medium text-gray-800">{{ $totalMerchants }}</p>
        <p class="text-xs text-green-600 mt-1">Terdaftar di platform</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 stat-card">
        <p class="text-xs text-gray-400 mb-1">Total Consumer</p>
        <p class="text-2xl font-medium text-gray-800">{{ $totalConsumers }}</p>
        <p class="text-xs text-green-600 mt-1">Pengguna aktif</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 stat-card">
        <p class="text-xs text-gray-400 mb-1">Menunggu Verifikasi</p>
        <p class="text-2xl font-medium text-amber-500">{{ $pendingMerchants }}</p>
        <p class="text-xs text-amber-400 mt-1">Perlu tindakan</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 stat-card">
        <p class="text-xs text-gray-400 mb-1">Total Produk Aktif</p>
        <p class="text-2xl font-medium text-gray-800">{{ $totalProducts }}</p>
        <p class="text-xs text-green-600 mt-1">Produk tersedia</p>
    </div>
</div>

{{-- Row 2: Transaksi Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    <div class="bg-white rounded-xl border border-gray-100 p-4 stat-card">
        <p class="text-xs text-gray-400 mb-1">Transaksi Hari Ini</p>
        <p class="text-2xl font-medium text-gray-800">{{ $todayTransactions }}</p>
        <p class="text-xs text-blue-500 mt-1">Total order masuk</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 stat-card">
        <p class="text-xs text-gray-400 mb-1">Revenue Hari Ini</p>
        <p class="text-lg font-medium text-gray-800">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
        <p class="text-xs text-blue-500 mt-1">Transaksi selesai</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 stat-card">
        <p class="text-xs text-gray-400 mb-1">Transaksi Minggu Ini</p>
        <p class="text-2xl font-medium text-gray-800">{{ $weekTransactions }}</p>
        <p class="text-xs text-purple-500 mt-1">Total order minggu ini</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 stat-card">
        <p class="text-xs text-gray-400 mb-1">Revenue Minggu Ini</p>
        <p class="text-lg font-medium text-gray-800">Rp {{ number_format($weekRevenue, 0, ',', '.') }}</p>
        <p class="text-xs text-purple-500 mt-1">Transaksi selesai</p>
    </div>
</div>

{{-- AI Insight Card --}}
<div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100 rounded-xl p-4 mb-4">
    <div class="flex items-center gap-2.5 mb-3">
        <div class="w-7 h-7 bg-green-600 rounded-lg flex items-center justify-center shrink-0">
            <span class="text-white text-xs font-medium">AI</span>
        </div>
        <div>
            <p class="text-sm font-medium text-green-800">AI Insight — Ringkasan Platform</p>
            <p class="text-xs text-green-600">Diperbarui otomatis setiap hari</p>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="bg-white/70 rounded-lg p-3">
            <p class="text-base font-medium text-green-700">{{ $savedFoodPercent }}%</p>
            <p class="text-xs text-green-600 mt-0.5">Tingkat penyelamatan makanan surplus minggu ini</p>
        </div>
        <div class="bg-white/70 rounded-lg p-3">
            <p class="text-base font-medium text-green-700">{{ number_format($estimatedWasteSaved, 1) }} kg</p>
            <p class="text-xs text-green-600 mt-0.5">Estimasi food waste yang berhasil dihindari</p>
        </div>
        <div class="bg-white/70 rounded-lg p-3">
            <p class="text-base font-medium text-green-700">{{ number_format($co2Saved, 1) }} kg CO₂</p>
            <p class="text-xs text-green-600 mt-0.5">Emisi karbon yang berhasil dikurangi</p>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <p class="text-xs font-medium text-gray-700 mb-3">Transaksi 7 Hari Terakhir</p>
        <div style="position:relative;height:160px">
            <canvas id="txChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <p class="text-xs font-medium text-gray-700 mb-3">Food Waste Terselamatkan (kg)</p>
        <div style="position:relative;height:160px">
            <canvas id="wasteChart"></canvas>
        </div>
    </div>
</div>

{{-- Bottom Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    {{-- Merchant Pending --}}
    <div class="bg-white rounded-xl border border-gray-100">
        <div class="px-4 py-3 border-b border-gray-50 flex items-center justify-between">
            <p class="text-xs font-medium text-gray-700">Merchant Menunggu Verifikasi</p>
            <a href="{{ route('admin.merchants') }}" class="text-xs text-green-600 hover:underline">Lihat semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs min-w-[400px]">
                <thead>
                    <tr class="bg-gray-50 text-gray-400">
                        <th class="text-left px-4 py-2.5 font-medium">Nama Toko</th>
                        <th class="text-left px-4 py-2.5 font-medium">Status</th>
                        <th class="text-left px-4 py-2.5 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($pendingList as $merchant)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $merchant->name }}</p>
                            <p class="text-gray-400 text-xs">{{ $merchant->address ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full text-xs">Pending</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-1.5 flex-wrap">
                                <form method="POST" action="{{ route('admin.merchants.approve', $merchant->id) }}">
                                    @csrf
                                    <button class="bg-green-600 text-white text-xs px-2.5 py-1 rounded-lg hover:bg-green-700">Setujui</button>
                                </form>
                                <form method="POST" action="{{ route('admin.merchants.reject', $merchant->id) }}">
                                    @csrf
                                    <button class="bg-red-50 text-red-600 text-xs px-2.5 py-1 rounded-lg hover:bg-red-100">Tolak</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-gray-400 text-xs">
                            Tidak ada merchant yang menunggu verifikasi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Status & Dampak --}}
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <p class="text-xs font-medium text-gray-700 mb-4">Status Merchant & Dampak Lingkungan</p>
        <div class="space-y-3 mb-4">
            @php
                $total    = max($totalMerchants, 1);
                $approved = \App\Models\Store::where('status','approved')->count();
                $rejected = \App\Models\Store::where('status','rejected')->count();
            @endphp
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-500">Disetujui</span>
                    <span class="font-medium text-green-600">{{ $approved }}</span>
                </div>
                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 rounded-full transition-all" style="width:{{ ($approved/$total)*100 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-500">Pending</span>
                    <span class="font-medium text-amber-500">{{ $pendingMerchants }}</span>
                </div>
                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-amber-400 rounded-full transition-all" style="width:{{ ($pendingMerchants/$total)*100 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-500">Ditolak</span>
                    <span class="font-medium text-red-500">{{ $rejected }}</span>
                </div>
                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-red-400 rounded-full transition-all" style="width:{{ ($rejected/$total)*100 }}%"></div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-50 pt-3 grid grid-cols-2 gap-3">
            <div>
                <p class="text-xs text-gray-400">Food Waste Dihindari</p>
                <p class="text-lg font-medium text-green-600 mt-0.5">{{ number_format($estimatedWasteSaved, 1) }} kg</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">CO₂ Dikurangi</p>
                <p class="text-lg font-medium text-green-600 mt-0.5">{{ number_format($co2Saved, 1) }} kg</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = {!! json_encode($last7Days->pluck('date')) !!};
const txData  = {!! json_encode($last7Days->pluck('total')) !!};
const wData   = {!! json_encode($last7Days->pluck('waste')) !!};
new Chart(document.getElementById('txChart'),{
    type:'bar',
    data:{labels,datasets:[{data:txData,backgroundColor:'rgba(22,163,74,0.12)',borderColor:'rgba(22,163,74,1)',borderWidth:1.5,borderRadius:4}]},
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{stepSize:1,font:{size:10}},grid:{color:'rgba(0,0,0,0.04)'}},x:{ticks:{font:{size:10}},grid:{display:false}}}}
});
new Chart(document.getElementById('wasteChart'),{
    type:'line',
    data:{labels,datasets:[{data:wData,backgroundColor:'rgba(16,185,129,0.08)',borderColor:'rgba(16,185,129,1)',borderWidth:1.5,fill:true,tension:0.4,pointRadius:3,pointBackgroundColor:'#10b981'}]},
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{font:{size:10}},grid:{color:'rgba(0,0,0,0.04)'}},x:{ticks:{font:{size:10}},grid:{display:false}}}}
});
</script>
@endsection
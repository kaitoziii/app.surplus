<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #1f2937; background: white; }

.header { padding: 20px 24px 16px; border-bottom: 2px solid #16a34a; display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
.logo { font-size: 18px; font-weight: bold; color: #16a34a; }
.logo span { font-size: 11px; color: #6b7280; font-weight: normal; display: block; margin-top: 2px; }
.report-info { text-align: right; }
.report-info p { color: #6b7280; font-size: 10px; line-height: 1.6; }
.report-info strong { color: #1f2937; font-size: 12px; }

.summary { display: flex; gap: 12px; padding: 0 24px 16px; }
.summary-card { flex: 1; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 14px; }
.summary-card .label { font-size: 9px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
.summary-card .value { font-size: 16px; font-weight: bold; color: #1f2937; }
.summary-card .value.green { color: #16a34a; }

.section-title { padding: 0 24px 8px; font-size: 11px; font-weight: bold; color: #374151; }

table { width: 100%; border-collapse: collapse; margin: 0 24px; width: calc(100% - 48px); }
thead tr { background: #16a34a; color: white; }
thead th { padding: 8px 10px; text-align: left; font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; }
tbody tr { border-bottom: 1px solid #f3f4f6; }
tbody tr:nth-child(even) { background: #f9fafb; }
tbody tr:hover { background: #f0fdf4; }
tbody td { padding: 7px 10px; font-size: 10px; color: #374151; }

.badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 600; }
.badge-green { background: #dcfce7; color: #15803d; }
.badge-blue { background: #dbeafe; color: #1d4ed8; }
.badge-amber { background: #fef3c7; color: #92400e; }
.badge-red { background: #fee2e2; color: #991b1b; }

.footer { margin-top: 20px; padding: 12px 24px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
.footer p { font-size: 9px; color: #9ca3af; }
.footer .brand { font-size: 10px; color: #16a34a; font-weight: bold; }

.empty { text-align: center; padding: 40px; color: #9ca3af; }
</style>
</head>
<body>

{{-- Header --}}
<div class="header">
    <div>
        <div class="logo">
            App.Surplus
            <span>Platform Penyelamatan Makanan Surplus</span>
        </div>
    </div>
    <div class="report-info">
        <strong>Laporan Transaksi</strong>
        <p>Digenerate: {{ $generatedAt }}</p>
        <p>Total data: {{ $transactions->count() }} transaksi</p>
    </div>
</div>

{{-- Summary Cards --}}
<div class="summary">
    <div class="summary-card">
        <div class="label">Total Transaksi</div>
        <div class="value">{{ $transactions->count() }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Total Revenue</div>
        <div class="value green">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Total Penghematan</div>
        <div class="value green">Rp {{ number_format($totalSavings, 0, ',', '.') }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Selesai (Picked Up)</div>
        <div class="value">{{ $transactions->where('status', 'picked_up')->count() }}</div>
    </div>
    <div class="summary-card">
        <div class="label">Food Waste Dicegah</div>
        <div class="value green">{{ round($transactions->where('status','picked_up')->count() * 0.3, 1) }} kg</div>
    </div>
</div>

{{-- Table --}}
<div class="section-title">Detail Transaksi</div>

@if($transactions->isEmpty())
<div class="empty">Tidak ada data transaksi</div>
@else
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Consumer</th>
            <th>Produk</th>
            <th>Qty</th>
            <th>Harga Asli</th>
            <th>Harga Bayar</th>
            <th>Diskon</th>
            <th>Hemat</th>
            <th>Status</th>
            <th>Pickup Code</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $trx)
        <tr>
            <td>#{{ $trx->id }}</td>
            <td>
                <strong>{{ $trx->user->name ?? '-' }}</strong><br>
                <span style="color:#9ca3af;font-size:9px">{{ $trx->user->email ?? '' }}</span>
            </td>
            <td>{{ $trx->product->name ?? '-' }}</td>
            <td style="text-align:center">{{ $trx->quantity }}</td>
            <td>Rp {{ number_format($trx->original_price_snapshot, 0, ',', '.') }}</td>
            <td><strong>Rp {{ number_format($trx->price_paid, 0, ',', '.') }}</strong></td>
            <td style="text-align:center">
                <span class="badge badge-green">{{ $trx->discount_applied }}%</span>
            </td>
            <td style="color:#16a34a">Rp {{ number_format($trx->savings_amount, 0, ',', '.') }}</td>
            <td>
                @if($trx->status === 'picked_up')
                    <span class="badge badge-green">Picked Up</span>
                @elseif($trx->status === 'confirmed')
                    <span class="badge badge-blue">Confirmed</span>
                @elseif($trx->status === 'cancelled')
                    <span class="badge badge-red">Cancelled</span>
                @else
                    <span class="badge badge-amber">Pending</span>
                @endif
            </td>
            <td style="font-family:monospace;font-size:9px">{{ $trx->pickup_code ?? '-' }}</td>
            <td style="color:#6b7280;font-size:9px">{{ $trx->created_at->format('d M Y H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Footer --}}
<div class="footer">
    <p>Dokumen ini digenerate otomatis oleh sistem App.Surplus</p>
    <div class="brand">App.Surplus — Mengurangi Food Waste Bersama</div>
</div>

</body>
</html>
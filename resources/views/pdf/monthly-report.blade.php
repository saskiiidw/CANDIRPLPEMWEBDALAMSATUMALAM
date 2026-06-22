<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan - {{ $storeName }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #331C0E;
            background: #fff;
            padding: 40px;
            line-height: 1.6;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #E27226;
            padding-bottom: 20px;
            margin-bottom: 28px;
        }
        .brand {
            font-size: 22px;
            font-weight: 900;
            color: #9E460B;
            letter-spacing: -0.5px;
        }
        .brand-sub {
            font-size: 10px;
            color: #8A7160;
            margin-top: 2px;
        }
        .report-meta {
            text-align: right;
        }
        .report-meta .title {
            font-size: 16px;
            font-weight: 700;
            color: #331C0E;
        }
        .report-meta .period {
            font-size: 11px;
            color: #8A7160;
            margin-top: 3px;
        }
        .report-meta .generated {
            font-size: 9px;
            color: #b0a09a;
            margin-top: 2px;
        }

        /* Store info box */
        .store-info {
            background: #FFF8F2;
            border: 1px solid #F4E1D2;
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 22px;
        }
        .store-info h2 { font-size: 14px; font-weight: 800; color: #331C0E; }
        .store-info p { font-size: 10px; color: #8A7160; margin-top: 2px; }

        /* Stats row */
        .stats-row {
            display: flex;
            gap: 14px;
            margin-bottom: 24px;
        }
        .stat-card {
            flex: 1;
            background: #FFF8F2;
            border: 1px solid #F4E1D2;
            border-radius: 8px;
            padding: 14px 16px;
            text-align: center;
        }
        .stat-card .label {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #8A7160;
            margin-bottom: 6px;
        }
        .stat-card .value {
            font-size: 18px;
            font-weight: 900;
            color: #9E460B;
        }
        .stat-card .sub {
            font-size: 9px;
            color: #b0a09a;
            margin-top: 2px;
        }

        /* Table */
        .section-title {
            font-size: 12px;
            font-weight: 800;
            color: #331C0E;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 1px solid #F4E1D2;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 26px;
        }
        thead tr {
            background: #9E460B;
            color: #fff;
        }
        thead th {
            padding: 9px 12px;
            text-align: left;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        thead th:last-child { text-align: right; }
        tbody tr { border-bottom: 1px solid #F4E1D2; }
        tbody tr:nth-child(even) { background: #FFFBF7; }
        tbody td {
            padding: 8px 12px;
            font-size: 10px;
            color: #331C0E;
        }
        tbody td:last-child { text-align: right; font-weight: 700; }
        .rank-badge {
            display: inline-block;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #E27226;
            color: #fff;
            font-size: 8px;
            font-weight: 800;
            text-align: center;
            line-height: 18px;
        }
        .rank-badge.gold { background: #D4AF37; }
        .rank-badge.silver { background: #A8A9AD; }
        .rank-badge.bronze { background: #CD7F32; }

        /* Orders table */
        .status-badge {
            padding: 2px 7px;
            border-radius: 20px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .status-selesai { background: #d1fae5; color: #065f46; }
        .status-ditolak { background: #fee2e2; color: #991b1b; }
        .status-lain { background: #FFF1E5; color: #9E460B; }

        /* Footer */
        .footer {
            margin-top: 40px;
            border-top: 1px solid #F4E1D2;
            padding-top: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .footer p { font-size: 9px; color: #b0a09a; }
        .footer .disclaimer {
            font-size: 9px;
            color: #b0a09a;
            font-style: italic;
        }

        /* Watermark-ish accent */
        .accent-bar {
            height: 5px;
            background: linear-gradient(to right, #9E460B, #E27226);
            border-radius: 3px;
            margin-bottom: 22px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <div class="brand">SmartCanteen</div>
            <div class="brand-sub">Platform Pemesanan Kantin Digital</div>
        </div>
        <div class="report-meta">
            <div class="title">Laporan Bulanan</div>
            <div class="period">{{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}</div>
            <div class="generated">Dibuat: {{ now()->translatedFormat('d F Y, H:i') }}</div>
        </div>
    </div>

    <div class="accent-bar"></div>

    <div class="store-info">
        <h2>{{ $storeName }}</h2>
        <p>Penjual Terverifikasi · {{ auth()->user()->email }}</p>
    </div>

    <!-- Summary Stats -->
    <div class="section-title">Ringkasan Kinerja Bulan Ini</div>
    <div class="stats-row">
        <div class="stat-card">
            <div class="label">Total Pendapatan</div>
            <div class="value">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
            <div class="sub">Dari pesanan selesai</div>
        </div>
        <div class="stat-card">
            <div class="label">Total Pesanan</div>
            <div class="value">{{ number_format($summary['total_orders']) }}</div>
            <div class="sub">Semua status</div>
        </div>
        <div class="stat-card">
            <div class="label">Pesanan Selesai</div>
            <div class="value">{{ number_format($summary['completed_orders']) }}</div>
            <div class="sub">Berhasil diselesaikan</div>
        </div>
        <div class="stat-card">
            <div class="label">Rata-rata Per Hari</div>
            <div class="value">Rp {{ number_format($summary['avg_daily_revenue'], 0, ',', '.') }}</div>
            <div class="sub">Rata-rata pendapatan</div>
        </div>
    </div>

    <!-- Top Items -->
    <div class="section-title">Item Terlaris Bulan Ini</div>
    <table>
        <thead>
            <tr>
                <th style="width:40px">#</th>
                <th>Nama Menu</th>
                <th>Total Terjual</th>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topItems as $i => $item)
            <tr>
                <td>
                    <span class="rank-badge {{ $i === 0 ? 'gold' : ($i === 1 ? 'silver' : ($i === 2 ? 'bronze' : '')) }}">
                        {{ $i + 1 }}
                    </span>
                </td>
                <td>{{ $item->menu_name_snapshot }}</td>
                <td>{{ number_format($item->total_qty) }} porsi</td>
                <td>Rp {{ number_format($item->total_subtotal, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center; color:#8A7160; padding: 20px;">
                    Belum ada data penjualan untuk bulan ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Recent Orders -->
    <div class="section-title">Riwayat Pesanan Bulan Ini (50 Terakhir)</div>
    <table>
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Tanggal</th>
                <th>Nama Pembeli</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $order->buyer->name ?? '-' }}</td>
                <td>
                    <span class="status-badge {{ $order->status === 'selesai' ? 'status-selesai' : ($order->status === 'ditolak' ? 'status-ditolak' : 'status-lain') }}">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </td>
                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; color:#8A7160; padding: 20px;">
                    Belum ada pesanan bulan ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>SmartCanteen · Laporan otomatis · {{ now()->format('d/m/Y H:i') }}</p>
        <p class="disclaimer">Dokumen ini dibuat secara otomatis oleh sistem SmartCanteen dan bersifat resmi.</p>
    </div>

</body>
</html>

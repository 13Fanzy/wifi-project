<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran WiFi - {{ $namaBulan[(int)$bulanAngka] }} {{ $tahun }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9pt;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4F46E5;
        }

        .header h1 {
            font-size: 16pt;
            color: #4F46E5;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 10pt;
            color: #666;
        }

        .filter-info {
            background: #f3f4f6;
            padding: 8px 12px;
            border-radius: 4px;
            margin-bottom: 12px;
            font-size: 9pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th {
            background-color: #4F46E5;
            color: white;
            font-weight: bold;
            padding: 8px 5px;
            text-align: left;
            font-size: 8pt;
        }

        td {
            padding: 6px 5px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 8pt;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .status-lunas {
            background-color: #DEF7EC;
            color: #03543F;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7pt;
        }

        .status-belum {
            background-color: #FEF3C7;
            color: #92400E;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7pt;
        }

        .status-nunggak {
            background-color: #FEE2E2;
            color: #991B1B;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7pt;
        }

        .aktif {
            color: #059669;
            font-weight: bold;
        }

        .nonaktif {
            color: #9CA3AF;
        }

        .footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 8pt;
            color: #666;
            text-align: center;
        }

        .summary {
            display: inline-block;
            margin: 0 15px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Pembayaran WiFi</h1>
        <p>Periode: {{ $namaBulan[(int)$bulanAngka] }} {{ $tahun }}</p>
    </div>

    <div class="filter-info">
        <strong>Filter:</strong>
        @if($filter === 'sudah_bayar')
        Sudah Bayar
        @elseif($filter === 'belum_bayar')
        Belum Bayar
        @else
        Semua Pelanggan
        @endif
        &nbsp;|&nbsp;
        <strong>Total Data:</strong> {{ $customers->count() }} pelanggan
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 25px;">No</th>
                <th style="width: 120px;">Nama</th>
                <th style="width: 90px;">No. WA</th>
                <th style="width: 140px;">Alamat</th>
                <th class="text-right" style="width: 70px;">Paket</th>
                <th class="text-center" style="width: 50px;">Status</th>
                <th class="text-center" style="width: 80px;">Pembayaran</th>
                <th class="text-right" style="width: 70px;">Jumlah</th>
                <th style="width: 85px;">Tgl Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $index => $customer)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $customer->nama }}</td>
                <td>{{ $customer->nomor_wa ?? '-' }}</td>
                <td>{{ Str::limit($customer->alamat ?? '-', 30) }}</td>
                <td class="text-right">{{ number_format($customer->paket_harga, 0, ',', '.') }}</td>
                <td class="text-center">
                    @if($customer->status_aktif)
                    <span class="aktif">Aktif</span>
                    @else
                    <span class="nonaktif">Nonaktif</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($customer->sudah_bayar && $customer->payment)
                    <span class="status-lunas">{{ $customer->payment->status_pembayaran }}</span>
                    @elseif($customer->is_overdue)
                    <span class="status-nunggak">Menunggak</span>
                    @else
                    <span class="status-belum">Belum Bayar</span>
                    @endif
                </td>
                <td class="text-right">
                    @if($customer->sudah_bayar && $customer->payment)
                    {{ number_format($customer->payment->jumlah_bayar, 0, ',', '.') }}
                    @else
                    -
                    @endif
                </td>
                <td>
                    @if($customer->sudah_bayar && $customer->payment)
                    {{ $customer->payment->tanggal_bayar->format('d/m/Y') }}
                    @else
                    -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <span class="summary">Diekspor pada: {{ $exportDate }}</span>
        <span class="summary">|</span>
        <span class="summary">WiFi Management System</span>
    </div>
</body>

</html>
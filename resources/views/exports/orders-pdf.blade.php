<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .period {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #0d9488;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        @media print {
            body { margin: 10mm; }
        }
    </style>
</head>
<body>
    <h1>Laporan Order - AC Service</h1>
    <p class="period">Periode: {{ $startDate }} - {{ $endDate }}</p>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Order</th>
                <th>Customer</th>
                <th>Telepon</th>
                <th>Layanan</th>
                <th>Kapasitas</th>
                <th>Unit</th>
                <th>Teknisi</th>
                <th>Tanggal</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $order->order_code }}</td>
                <td>{{ $order->customer->name }}</td>
                <td>{{ $order->customer->phone }}</td>
                <td>{{ $order->service->name }}</td>
                <td>{{ strtoupper($order->ac_capacity) }}</td>
                <td>{{ $order->ac_quantity }}</td>
                <td>{{ $order->technician?->name ?? '-' }}</td>
                <td>{{ $order->scheduled_date->format('d/m/Y') }}</td>
                <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
            @php $grandTotal += $order->total_price; @endphp
            @empty
            <tr>
                <td colspan="10" style="text-align:center;color:#999;">Tidak ada data order selesai pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        @if($orders->count() > 0)
        <tfoot>
            <tr class="total-row">
                <td colspan="9" class="text-right">Total Pendapatan:</td>
                <td class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>
    
    <p style="margin-top:30px;color:#999;font-size:10px;">
        Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}
    </p>
</body>
</html>

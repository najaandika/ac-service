<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $order->order_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .container {
            padding: 30px;
        }
        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 2px solid #0891B2;
            padding-bottom: 20px;
        }
        .header-left {
            display: table-cell;
            width: 60%;
            vertical-align: middle;
        }
        .header-right {
            display: table-cell;
            width: 40%;
            text-align: right;
            vertical-align: middle;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #0891B2;
            margin-bottom: 5px;
        }
        .company-info {
            font-size: 11px;
            color: #666;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }
        .invoice-code {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        .info-box {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .info-title {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .info-content {
            font-size: 12px;
        }
        .info-content p {
            margin-bottom: 3px;
        }
        .info-content strong {
            font-weight: bold;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        table.items th {
            background: #0891B2;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        table.items th:last-child {
            text-align: right;
        }
        table.items td {
            padding: 12px 8px;
            border-bottom: 1px solid #eee;
            font-size: 12px;
        }
        table.items td:last-child {
            text-align: right;
        }
        table.items tr:nth-child(even) {
            background: #f9f9f9;
        }
        .totals {
            width: 300px;
            margin-left: auto;
            margin-bottom: 30px;
        }
        .totals-row {
            display: table;
            width: 100%;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .totals-row.grand-total {
            border-bottom: none;
            border-top: 2px solid #0891B2;
            font-size: 16px;
            font-weight: bold;
            padding-top: 12px;
        }
        .totals-label {
            display: table-cell;
            width: 50%;
        }
        .totals-value {
            display: table-cell;
            width: 50%;
            text-align: right;
        }
        .discount {
            color: #22C55E;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background: #FEF3C7; color: #D97706; }
        .status-confirmed { background: #DBEAFE; color: #2563EB; }
        .status-in_progress { background: #E0E7FF; color: #4F46E5; }
        .status-completed { background: #D1FAE5; color: #059669; }
        .status-cancelled { background: #FEE2E2; color: #DC2626; }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 11px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .notes {
            background: #F3F4F6;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .notes-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <div class="company-name">{{ $settings['site_name'] ?? 'AC Service' }}</div>
                <div class="company-info">
                    @if(!empty($settings['address']))
                        {{ $settings['address'] }}<br>
                    @endif
                    @if(!empty($settings['phone']))
                        Telp: {{ $settings['phone'] }}
                    @endif
                    @if(!empty($settings['email']))
                        | Email: {{ $settings['email'] }}
                    @endif
                </div>
            </div>
            <div class="header-right">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-code">#{{ $order->order_code }}</div>
            </div>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <div class="info-box">
                <div class="info-title">Tagihan Kepada</div>
                <div class="info-content">
                    <p><strong>{{ $order->customer->name }}</strong></p>
                    <p>{{ $order->customer->phone }}</p>
                    @if($order->customer->email)
                        <p>{{ $order->customer->email }}</p>
                    @endif
                    <p>{{ $order->address }}</p>
                </div>
            </div>
            <div class="info-box">
                <div class="info-title">Detail Invoice</div>
                <div class="info-content">
                    <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y') }}</p>
                    <p><strong>Jadwal Layanan:</strong> {{ \Carbon\Carbon::parse($order->scheduled_date)->format('d M Y') }} - {{ $order->scheduled_time }}</p>
                    <p><strong>Status:</strong> 
                        <span class="status-badge status-{{ $order->status }}">
                            {{ $order->status_label }}
                        </span>
                    </p>
                    @if($order->technician)
                        <p><strong>Teknisi:</strong> {{ $order->technician->name }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items">
            <thead>
                <tr>
                    <th style="width: 40%">Layanan</th>
                    <th style="width: 20%">Detail AC</th>
                    <th style="width: 15%">Jumlah</th>
                    <th style="width: 25%">Harga</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $order->service->name }}</strong>
                    </td>
                    <td>
                        {{ ucfirst($order->ac_type) }} - {{ strtoupper($order->ac_capacity) }}
                    </td>
                    <td>{{ $order->ac_quantity }} unit</td>
                    <td>Rp {{ number_format($order->base_price, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="totals-row">
                <div class="totals-label">Subtotal</div>
                <div class="totals-value">Rp {{ number_format($order->base_price, 0, ',', '.') }}</div>
            </div>
            @if($order->promo && $order->discount_amount > 0)
            <div class="totals-row">
                <div class="totals-label">Diskon ({{ $order->promo->code }})</div>
                <div class="totals-value discount">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</div>
            </div>
            @endif
            <div class="totals-row grand-total">
                <div class="totals-label">Total</div>
                <div class="totals-value">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Notes -->
        @if($order->notes)
        <div class="notes">
            <div class="notes-title">Catatan:</div>
            {{ $order->notes }}
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih telah menggunakan layanan kami.</p>
            <p>{{ $settings['site_name'] ?? 'AC Service' }} - {{ $settings['phone'] ?? '' }}</p>
        </div>
    </div>
</body>
</html>

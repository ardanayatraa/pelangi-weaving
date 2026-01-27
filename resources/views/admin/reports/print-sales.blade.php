<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - {{ $dateFrom }} s/d {{ $dateTo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header h2 {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 14px;
            color: #888;
        }
        
        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 20px;
        }
        
        .summary-card {
            flex: 1;
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            background: #f9f9f9;
        }
        
        .summary-card h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
        }
        
        .summary-card .number {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .summary-card .amount {
            font-size: 12px;
            color: #888;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section h3 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        table th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        table td {
            font-size: 11px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .status.completed { background: #d4edda; color: #155724; }
        .status.pending { background: #fff3cd; color: #856404; }
        .status.cancelled { background: #f8d7da; color: #721c24; }
        .status.approved { background: #cce5ff; color: #004085; }
        .status.in-production { background: #e2e3ff; color: #383d41; }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        @media print {
            body { font-size: 11px; }
            .header h1 { font-size: 20px; }
            .header h2 { font-size: 16px; }
            .summary-card .number { font-size: 18px; }
            .section h3 { font-size: 14px; }
            table th, table td { padding: 6px; }
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #0056b3;
        }
        
        @media print {
            .print-button { display: none; }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">Print Laporan</button>
    
    <div class="header">
        <h1>PELANGI TRADITIONAL WEAVING SIDEMEN</h1>
        <h2>LAPORAN PENJUALAN</h2>
        <p>Periode: {{ \Carbon\Carbon::parse($dateFrom)->format('d F Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d F Y') }}</p>
        <p>Jenis Laporan: {{ ucfirst($reportType == 'all' ? 'Semua' : ($reportType == 'regular' ? 'Pesanan Regular' : 'Custom Orders')) }}</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>
    
    <div class="summary">
        <div class="summary-card">
            <h3>Pesanan Regular</h3>
            <div class="number">{{ $stats['regular_orders']['count'] }}</div>
            <div class="amount">Rp {{ number_format($stats['regular_orders']['total_revenue'], 0, ',', '.') }}</div>
        </div>
        
        <div class="summary-card">
            <h3>Custom Orders</h3>
            <div class="number">{{ $stats['custom_orders']['count'] }}</div>
            <div class="amount">Rp {{ number_format($stats['custom_orders']['total_revenue'], 0, ',', '.') }}</div>
        </div>
        
        <div class="summary-card">
            <h3>Total Revenue</h3>
            <div class="number">Rp {{ number_format($stats['regular_orders']['total_revenue'] + $stats['custom_orders']['total_revenue'], 0, ',', '.') }}</div>
            <div class="amount">{{ $stats['regular_orders']['count'] + $stats['custom_orders']['count'] }} Transaksi</div>
        </div>
        
        <div class="summary-card">
            <h3>Rata-rata Order</h3>
            <div class="number">Rp {{ number_format(($stats['regular_orders']['avg_order_value'] + $stats['custom_orders']['avg_order_value']) / 2, 0, ',', '.') }}</div>
            <div class="amount">Per Transaksi</div>
        </div>
    </div>
    
    @if($topProducts->count() > 0)
    <div class="section">
        <h3>Produk Terlaris</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th class="text-center">Qty Terjual</th>
                    <th class="text-right">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $index => $product)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $product->nama_produk }}</td>
                    <td class="text-center">{{ $product->total_qty }}</td>
                    <td class="text-right">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    @if(($reportType === 'all' || $reportType === 'regular') && $regularOrders->count() > 0)
    <div class="section">
        <h3>Detail Pesanan Regular</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Invoice</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th class="text-center">Status</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($regularOrders as $index => $order)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $order->nomor_invoice }}</td>
                    <td>{{ $order->pelanggan->nama }}</td>
                    <td>{{ $order->tanggal_pesanan->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <span class="status {{ $order->status_pesanan === 'selesai' ? 'completed' : ($order->status_pesanan === 'batal' ? 'cancelled' : 'pending') }}">
                            {{ ucfirst($order->status_pesanan) }}
                        </span>
                    </td>
                    <td class="text-right">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #f5f5f5; font-weight: bold;">
                    <td colspan="5" class="text-right">TOTAL:</td>
                    <td class="text-right">Rp {{ number_format($regularOrders->sum('total_bayar'), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
    
    @if(($reportType === 'all' || $reportType === 'custom') && $customOrders->count() > 0)
    <div class="section">
        <h3>Detail Custom Orders</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Order ID</th>
                    <th>Pelanggan</th>
                    <th>Jenis</th>
                    <th>Tanggal</th>
                    <th class="text-center">Status</th>
                    <th class="text-right">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customOrders as $index => $order)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $order->nomor_custom_order }}</td>
                    <td>{{ $order->pelanggan->nama }}</td>
                    <td>{{ $order->jenis->nama_jenis }}</td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <span class="status {{ $order->status }}">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </td>
                    <td class="text-right">
                        @if($order->harga_final > 0)
                            Rp {{ number_format($order->harga_final, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #f5f5f5; font-weight: bold;">
                    <td colspan="6" class="text-right">TOTAL:</td>
                    <td class="text-right">Rp {{ number_format($customOrders->where('harga_final', '>', 0)->sum('harga_final'), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
    
    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem Pelangi Traditional Weaving Sidemen</p>
        <p>Untuk informasi lebih lanjut, hubungi admin sistem</p>
    </div>
    
    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }
        
        // Print function
        function printReport() {
            window.print();
        }
    </script>
</body>
</html>